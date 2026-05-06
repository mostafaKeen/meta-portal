<?php

namespace Modules\ConversionAPI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Modules\Company\Models\Company;
use Modules\ConversionAPI\Models\ConversionLog;

class ConversionController extends Controller
{
    /**
     * Handle incoming Bitrix24 webhook for Meta CAPI
     */
    public function handle(Request $request, string $token)
    {
        // 1️⃣ Resolve Company by CAPI Outbound Token
        $company = Company::where('capi_outbound_token', $token)
            ->active()
            ->first();

        if (!$company) {
            return response()->json(['error' => 'Invalid token or inactive company'], 404);
        }

        // Prepare base log data
        $logData = [
            'company_id'      => $company->id,
            'entity_type'     => 'UNKNOWN',
            'entity_id'       => 0,
            'event_name'      => $request->query('event_name', 'Lead'),
            'bitrix_payload'  => [
                'headers' => $request->headers->all(),
                'body'    => $request->all(),
            ],
            'fb_payload'      => null,
            'fb_response'     => null,
            'status'          => 'failed',
            'error_message'   => null,
        ];


        try {
            // 2️⃣ Extract entity from Bitrix webhook (document_id.2 is usually LEAD_123 or DEAL_123)
            $doc = data_get($request->all(), 'document_id.2');
            if (!$doc || !Str::startsWith($doc, ['LEAD_', 'DEAL_'])) {
                $logData['status'] = 'ignored';
                $logData['error_message'] = 'Invalid or non-supported entity ID: ' . ($doc ?? 'null');
                ConversionLog::create($logData);
                return response()->json(['status' => 'ignored']);
            }

            $entityType = Str::startsWith($doc, 'DEAL_') ? 'DEAL' : 'LEAD';
            $entityId   = (int) Str::replace(['LEAD_', 'DEAL_'], '', $doc);
            
            $logData['entity_type'] = $entityType;
            $logData['entity_id']   = $entityId;

            // 3️⃣ Fetch entity + fields from Bitrix
            // Note: We use the company's Bitrix credentials stored in the database
            $item = $this->bitrixCall(
                $company,
                $entityType === 'LEAD' ? 'crm.lead.get' : 'crm.deal.get',
                ['id' => $entityId]
            )['result'] ?? null;

            $fields = $this->bitrixCall(
                $company,
                $entityType === 'LEAD' ? 'crm.lead.fields' : 'crm.deal.fields'
            )['result'] ?? [];

            if (!$item || !$fields) {
                $logData['status'] = 'failed';
                $logData['error_message'] = 'Failed to fetch entity details or fields from Bitrix';
                ConversionLog::create($logData);
                return response()->json(['status' => 'failed'], 500);
            }

            // 4️⃣ Map data by LABEL (using heuristics from the bridge project)
            $labeledData = $this->mapByLabel($item, $fields);

            // 5️⃣ Extract required fields (Name, Phone, Email, City)
            $name  = $this->extractName($labeledData, $item);
            $phone = $this->extractPhone($labeledData, $item);
            $email = $this->extractEmail($labeledData, $item);
            $city  = $this->extractCity($labeledData, $item);

            // 6️⃣ Validate required fields
            $missing = [];
            if (empty($name['first_name'])) $missing[] = 'first_name';
            if (empty($phone))              $missing[] = 'phone';
            if (empty($email))              $missing[] = 'email';

            if (!empty($missing)) {
                $logData['status'] = 'failed';
                $logData['error_message'] = 'Missing required user data: ' . implode(', ', $missing);
                ConversionLog::create($logData);
                return response()->json(['status' => 'failed', 'missing' => $missing], 422);
            }

            // 7️⃣ Prepare user_data with SHA256 hashes
            $userData = [
                'fn' => $this->hashForFB($name['first_name']),
                'ln' => $this->hashForFB($name['last_name'] ?? ''),
                'ph' => $this->hashForFB($phone),
                'em' => $this->hashForFB($email),
            ];

            if (!empty($city)) {
                $userData['ct'] = $this->hashForFB($city);
            }

            // 8️⃣ Determine event type (Default to 'Lead' or 'Purchase' depending on entity)
            // Can be overridden via query parameter e.g. ?event_name=Schedule
            $defaultEvent = ($entityType === 'DEAL') ? 'Purchase' : 'Lead';
            $eventName = $request->query('event_name', $defaultEvent);
            $logData['event_name'] = $eventName;

            $customData = [
                'lead_id' => (string) $entityId,
            ];

            // 9️⃣ Handle Purchase specifics
            if ($entityType === 'DEAL' && $eventName === 'Purchase') {
                $customData['value']        = isset($labeledData['OPPORTUNITY']) ? (float) $labeledData['OPPORTUNITY'] : 0;
                $customData['currency']     = $labeledData['CURRENCY_ID'] ?? 'USD';
                $customData['content_type'] = 'product';
                $customData['content_name'] = $labeledData['TITLE'] ?? null;
            }

            // 1️⃣0️⃣ Build Meta CAPI payload
            $fbPayload = [
                'data' => [[
                    'event_name'    => $eventName,
                    'event_time'    => now()->timestamp,
                    'action_source' => 'system_generated',
                    'user_data'     => $userData,
                    'custom_data'   => $customData,
                ]],
            ];

            $logData['fb_payload'] = $fbPayload;

            // 1️⃣1️⃣ Send to Meta
            if (empty($company->fb_pixel_id) || empty($company->fb_access_token)) {
                $logData['status'] = 'failed';
                $logData['error_message'] = 'Meta Pixel ID or Access Token is missing for this company';
                ConversionLog::create($logData);
                return response()->json(['status' => 'failed', 'error' => 'CAPI not configured'], 400);
            }

            $response = Http::timeout(15)->post(
                "https://graph.facebook.com/v18.0/{$company->fb_pixel_id}/events",
                array_merge($fbPayload, ['access_token' => $company->fb_access_token])
            );

            $logData['fb_response'] = $response->json();
            $logData['status']      = $response->successful() ? 'success' : 'failed';
            if (!$response->successful()) {
                $logData['error_message'] = 'Meta API Error: ' . $response->body();
            }

        } catch (\Throwable $e) {
            $logData['status']        = 'failed';
            $logData['error_message'] = $e->getMessage();
            Log::error('ConversionController@handle exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }

        // 1️⃣2️⃣ Finalize Log
        ConversionLog::create($logData);

        return response()->json([
            'status' => $logData['status'],
            'message' => $logData['error_message'] ?? 'Event processed'
        ]);
    }

    // ─── Helpers (Adapted from Bridge Project) ────────────────────────────────

    private function bitrixCall(Company $company, string $method, array $payload = [])
    {
        $domain = trim($company->b24_domain);
        
        if (Str::contains($domain, 'rest/')) {
            // It's a full webhook URL (e.g. https://domain.bitrix24.com/rest/1/abc/)
            $url = rtrim($domain, '/') . '/' . $method . '.json';
        } else {
            // It's just a domain (e.g. domain.bitrix24.com)
            $url = "https://{$domain}/rest/{$method}.json";
        }
        
        // Sanity check: Fix double slashes (except after protocol)
        $url = preg_replace('/(?<!:)\/\/+/', '/', $url);
        
        $request = Http::timeout(15);

        // If it's NOT a full webhook (no /rest/TOKEN/), we might need auth param
        // But usually b24_access_token is used for OAuth apps.
        if (!Str::contains($domain, '/rest/')) {
            $payload['auth'] = $company->b24_access_token;
        }

        return $request->post($url, $payload)->json();
    }


    private function mapByLabel(array $item, array $fields): array
    {
        $mapped = [];
        foreach ($item as $code => $value) {
            $label = $fields[$code]['formLabel'] ?? $fields[$code]['listLabel'] ?? $fields[$code]['title'] ?? $code;
            $mapped[trim((string)$label)] = $value;
        }
        return $mapped;
    }

    private function extractPhone(array $labeledData, array $rawItem): ?string
    {
        if (!empty($rawItem['PHONE']) && is_array($rawItem['PHONE'])) {
            foreach ($rawItem['PHONE'] as $p) {
                if (!empty($p['VALUE'])) return (string)$p['VALUE'];
            }
        }

        foreach ($labeledData as $label => $value) {
            $l = strtolower(trim($label));
            if (Str::contains($l, ['phone', 'tel', 'mobile', 'cell'])) {
                if ($value === 'Y' || $value === 'N') continue;
                if (is_array($value)) return (string)($value[0]['VALUE'] ?? $value[0] ?? null);
                return (string)$value;
            }
        }
        return null;
    }

    private function extractEmail(array $labeledData, array $rawItem): ?string
    {
        if (!empty($rawItem['EMAIL']) && is_array($rawItem['EMAIL'])) {
            foreach ($rawItem['EMAIL'] as $e) {
                if (!empty($e['VALUE'])) return (string)$e['VALUE'];
            }
        }

        foreach ($labeledData as $label => $value) {
            $l = strtolower(trim($label));
            if (Str::contains($l, ['email', 'e-mail', 'mail'])) {
                if ($value === 'Y' || $value === 'N') continue;
                if (is_array($value)) return (string)($value[0]['VALUE'] ?? $value[0] ?? null);
                return (string)$value;
            }
        }
        return null;
    }

    private function extractCity(array $labeledData, array $rawItem): ?string
    {
        if (!empty($rawItem['ADDRESS_CITY'])) return trim($rawItem['ADDRESS_CITY']);

        foreach ($labeledData as $label => $value) {
            $l = strtolower(trim((string) $label));
            if (Str::contains($l, ['city', 'town'])) {
                if ($value === 'Y' || $value === 'N' || !$value) continue;
                if (is_string($value)) return trim($value);
                if (is_array($value)) return trim($value[0]['VALUE'] ?? $value[0] ?? '');
            }
        }
        return null;
    }

    private function extractName(array $labeledData, array $rawItem): array
    {
        $first = $rawItem['NAME'] ?? null;
        $last  = $rawItem['LAST_NAME'] ?? null;

        if (empty($last) && !empty($first)) {
            $parts = preg_split('/\s+/', trim((string)$first), 2);
            if (count($parts) > 1) {
                $first = $parts[0];
                $last  = $parts[1];
            }
        }

        if (empty($first) || empty($last)) {
            foreach ($labeledData as $label => $value) {
                $l = strtolower(trim($label));
                if (Str::contains($l, ['date', 'time', 'status', 'id', 'source', 'type'])) continue;

                $val = is_array($value) ? ($value[0]['VALUE'] ?? $value[0] ?? null) : $value;
                if (!$val || $val === 'Y' || $val === 'N') continue;

                if (empty($first) && Str::contains($l, ['first name', 'firstname', 'given name'])) {
                    $first = trim((string)$val);
                }
                if (empty($last) && (Str::contains($l, ['last name', 'lastname', 'surname']) || $l === 'last')) {
                    $last = trim((string)$val);
                }
                if ((empty($first) || empty($last)) && ($l === 'name' || $l === 'full name' || $l === 'fullname')) {
                    $parts = preg_split('/\s+/', trim((string)$val), 2);
                    if (empty($first)) $first = $parts[0] ?? null;
                    if (empty($last))  $last  = $parts[1] ?? null;
                }
            }
        }

        return [
            'first_name' => $first ? trim((string)$first) : null,
            'last_name'  => $last ? trim((string)$last) : null
        ];
    }

    private function hashForFB(string $value): string
    {
        $v = trim(strtolower($value));
        $v = preg_replace('/\s+/', '', $v);
        return hash('sha256', $v);
    }
}
