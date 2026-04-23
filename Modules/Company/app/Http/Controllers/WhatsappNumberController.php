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

        WhatsappNumber::create($data);

        return redirect()->route('company.whatsapp.index')
            ->with('success', 'WhatsApp number added successfully.');
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

        $whatsapp->delete();

        return redirect()->route('company.whatsapp.index')
            ->with('success', 'WhatsApp number deleted successfully.');
    }
}
