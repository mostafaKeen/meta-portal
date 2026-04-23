<?php

namespace Modules\Plans\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Plans\Models\SubscriptionRequest;
use Modules\Plans\Models\Subscription;
use App\Notifications\SubscriptionRequestStatusChanged;
use Carbon\Carbon;

class SubscriptionRequestController extends Controller
{
    /**
     * Display a listing of custom requests.
     */
    public function index()
    {
        $requests = SubscriptionRequest::with(['company', 'plan'])
            ->latest()
            ->paginate(15);
            
        return view('plans::admin.requests.index', compact('requests'));
    }

    /**
     * Display the specified request details.
     */
    public function show(SubscriptionRequest $request)
    {
        $request->load(['company', 'plan']);
        return view('plans::admin.requests.show', compact('request'));
    }

    /**
     * Update the status of a request (Approve/Reject).
     */
    public function update(Request $request, SubscriptionRequest $subRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $subRequest->update($validated);

        if ($validated['status'] === 'approved') {
            $this->processApproval($subRequest);
        }

        // Notify Company Admin
        if ($subRequest->company->admin) {
            $subRequest->company->admin->notify(new SubscriptionRequestStatusChanged($subRequest));
        }

        return redirect()->route('admin.requests.index')
            ->with('success', "Request {$validated['status']} successfully.");
    }

    /**
     * Handle the core subscription logic on approval.
     */
    protected function processApproval(SubscriptionRequest $subRequest)
    {
        $company = $subRequest->company;
        $plan = $subRequest->plan;

        // Cancel previous active subscriptions
        Subscription::where('company_id', $company->id)
            ->where('status', 'active')
            ->update(['status' => 'canceled', 'ends_at' => Carbon::now()]);

        // Calculate dates
        $startsAt = Carbon::now();
        $endsAt = $plan->billing_cycle === 'monthly' ? $startsAt->copy()->addMonth() : $startsAt->copy()->addYear();

        // Create new active subscription
        Subscription::create([
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ]);
        
        // Note: In a real payment gateway scenario, we would link the transaction ID here.
    }
}
