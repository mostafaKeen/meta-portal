<?php

namespace Modules\Plans\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Plans\Models\SubscriptionRequest;
use Modules\Plans\Models\Plan;

class SubscriptionRequestController extends Controller
{
    /**
     * Store a newly created subscription request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'user_notes' => 'nullable|string|max:500',
        ]);

        $company = auth()->user()->company;
        $activeSubscription = $company->subscription;
        
        // Determine request type
        $type = 'new';
        if ($activeSubscription) {
            if ($activeSubscription->plan_id == $validated['plan_id']) {
                $type = 'renew';
            } else {
                $type = 'change';
            }
        }

        // Prevent multiple pending requests for the same plan
        $exists = SubscriptionRequest::where('company_id', $company->id)
            ->where('plan_id', $validated['plan_id'])
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already have a pending request for this plan.');
        }

        $subRequest = SubscriptionRequest::create([
            'company_id' => $company->id,
            'plan_id' => $validated['plan_id'],
            'type' => $type,
            'user_notes' => $validated['user_notes'],
            'status' => 'pending',
        ]);

        // Notify Super Admins
        $superAdmins = \App\Models\User::where('role', 'super_admin')->get();
        \Illuminate\Support\Facades\Notification::send($superAdmins, new \App\Notifications\NewSubscriptionRequest($subRequest));

        return redirect()->route('company.subscription.index')
            ->with('success', 'Your subscription request has been sent to the administrator for approval.');
    }
}
