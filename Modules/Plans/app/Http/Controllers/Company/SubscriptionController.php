<?php

namespace Modules\Plans\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Plans\Models\Plan;
use Modules\Plans\Models\SubscriptionRequest;

class SubscriptionController extends Controller
{
    /**
     * Display the current company subscription and request history.
     */
    public function index()
    {
        $company = auth()->user()->company;
        $subscription = $company->subscription;
        $pendingRequests = SubscriptionRequest::where('company_id', $company->id)
            ->where('status', 'pending')
            ->latest()
            ->get();
            
        return view('plans::company.subscription.index', compact('subscription', 'pendingRequests'));
    }

    /**
     * Display all active plans for the company to choose from.
     */
    public function plans()
    {
        $company = auth()->user()->company;
        $activeSubscription = $company->subscription;
        $plans = Plan::where('is_active', true)->orderBy('price', 'asc')->get();
        $pendingRequests = SubscriptionRequest::where('company_id', $company->id)
            ->where('status', 'pending')
            ->get()
            ->pluck('plan_id')
            ->toArray();

        return view('plans::company.subscription.plans', compact('plans', 'activeSubscription', 'pendingRequests'));
    }
}
