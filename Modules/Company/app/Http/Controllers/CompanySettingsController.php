<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Company\Http\Requests\UpdateCompanySettingsRequest;

class CompanySettingsController extends Controller
{
    /**
     * Show the form for editing the company settings.
     */
    public function edit()
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->back()->with('error', 'No company associated with this user.');
        }

        return view('company::settings.edit', compact('company'));
    }

    /**
     * Update the company settings.
     */
    public function update(UpdateCompanySettingsRequest $request)
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->back()->with('error', 'No company associated with this user.');
        }

        $data = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        // Ensure booleans for checkboxes
        $data['qr']  = $request->boolean('qr');
        $data['api'] = $request->boolean('api');

        $company->update($data);

        return redirect()->route('company.settings.edit')
            ->with('success', 'Company settings updated successfully.');
    }
}
