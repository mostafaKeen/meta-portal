<?php

namespace Modules\ConversionAPI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CapiSettingsController extends Controller
{
    /**
     * Generate a new CAPI outbound token for the company.
     */
    public function generateToken()
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->back()->with('error', 'No company associated with this user.');
        }

        $company->generateCapiToken();

        return redirect()->back()->with('success', 'Meta CAPI outbound token generated successfully.');
    }
}
