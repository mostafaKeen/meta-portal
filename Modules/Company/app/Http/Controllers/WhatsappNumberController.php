<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Company\Models\WhatsappNumber;
use Modules\Company\Http\Requests\StoreWhatsappNumberRequest;
use Modules\Company\Http\Requests\UpdateWhatsappNumberRequest;

class WhatsappNumberController extends Controller
{
    /**
     * Display a listing of WhatsApp numbers for the company.
     */
    public function index()
    {
        $company = auth()->user()->company;
        $numbers = $company->whatsappNumbers()->latest()->get();

        return view('company::whatsapp.index', compact('numbers'));
    }

    /**
     * Show the form for creating a new number.
     */
    public function create()
    {
        return view('company::whatsapp.create');
    }

    /**
     * Store a newly created number.
     */
    public function store(StoreWhatsappNumberRequest $request)
    {
        $company = auth()->user()->company;
        $data = $request->validated();

        if (isset($data['type']) && $data['type'] === 'qr' && $company->hasReachedWhatsappLimit()) {
            return redirect()->back()
                ->with('error', 'You have reached the maximum number of WhatsApp QR numbers allowed for your plan.')
                ->withInput();
        }

        $data['company_id'] = $company->id;

        if ($data['type'] === 'qr') {
            $data['session_name'] = $data['session_name'] ?? 'session_' . $company->id . '_' . time();
        }

        $number = WhatsappNumber::create($data);

        if ($number->type === 'qr') {
            // Start the session in the Node engine
            app(\Modules\WhatsAppQR\Services\WhatsAppService::class)->startSession($number->session_name);
        }

        return redirect()->route('company.whatsapp.index')
            ->with('success', 'WhatsApp number added successfully.');
    }

    /**
     * Display the specified number and its chats.
     */
    public function show(WhatsappNumber $whatsapp)
    {
        if ($whatsapp->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $chats = $whatsapp->chats()->with(['messages' => function($q) {
            $q->latest()->limit(50);
        }])->latest('last_message_at')->get();

        return view('company::whatsapp.show', compact('whatsapp', 'chats'));
    }

    /**
     * Show the form for editing the specified number.
     */
    public function edit(WhatsappNumber $whatsapp)
    {
        // Ensure tenant isolation
        if ($whatsapp->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('company::whatsapp.edit', compact('whatsapp'));
    }

    /**
     * Update the specified number.
     */
    public function update(UpdateWhatsappNumberRequest $request, WhatsappNumber $whatsapp)
    {
        if ($whatsapp->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $whatsapp->update($request->validated());

        return redirect()->route('company.whatsapp.index')
            ->with('success', 'WhatsApp number updated successfully.');
    }

    /**
     * Remove the specified number.
     */
    public function destroy(WhatsappNumber $whatsapp)
    {
        if ($whatsapp->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        // Clean up the Node engine session before deleting the DB record
        if ($whatsapp->type === 'qr' && $whatsapp->session_name) {
            app(\Modules\WhatsAppQR\Services\WhatsAppService::class)->deleteSession($whatsapp->session_name);
        }

        $whatsapp->delete();

        return redirect()->route('company.whatsapp.index')
            ->with('success', 'WhatsApp number deleted successfully.');
    }
}
