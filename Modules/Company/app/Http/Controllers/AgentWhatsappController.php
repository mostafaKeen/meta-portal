<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentWhatsappController extends Controller
{
    /**
     * Display a listing of assigned WhatsApp numbers for the agent.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isAgent()) {
            abort(403, 'Unauthorized access.');
        }

        $assignedNumbers = $user->whatsappNumbers()->with('company')->get();

        return view('company::agent.whatsapp.index', compact('assignedNumbers'));
    }
}
