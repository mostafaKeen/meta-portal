<?php

namespace Modules\WhatsAppQR\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\WhatsAppQR\Services\WhatsAppService;

class WhatsAppSessionController extends Controller
{
    public function retry(Request $request)
    {
        $sessionId = $request->input('session_id');
        
        $response = app(WhatsAppService::class)->startSession($sessionId);
        
        return response()->json($response);
    }
}
