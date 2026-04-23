<?php

namespace Modules\Plans\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Plans\Models\Subscription;
use Modules\Plans\Models\Plan;
use Modules\Company\Models\Company;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['company', 'plan'])->latest()->paginate(15);
        return view('plans::admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $companies = Company::active()->get();
        $plans = Plan::where('is_active', true)->get();
        return view('plans::admin.subscriptions.create', compact('companies', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plan_id' => 'required|exists:plans,id',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'status' => 'required|in:active,past_due,canceled,trialing',
        ]);

        // Cancel previous active subscriptions for this company
        Subscription::where('company_id', $validated['company_id'])
            ->where('status', 'active')
            ->update(['status' => 'canceled', 'ends_at' => Carbon::now()]);

        $subscription = Subscription::create($validated);

        // Notify Company Admin
        if ($subscription->company->admin) {
            $subscription->company->admin->notify(new \App\Notifications\SubscriptionStatusChanged($subscription));
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription assigned successfully.');
    }

    public function edit(Subscription $subscription)
    {
        $plans = Plan::where('is_active', true)->get();
        return view('plans::admin.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'status' => 'required|in:active,past_due,canceled,trialing',
        ]);

        $subscription->update($validated);

        // Notify Company Admin
        if ($subscription->company->admin) {
            $subscription->company->admin->notify(new \App\Notifications\SubscriptionStatusChanged($subscription));
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }
}
