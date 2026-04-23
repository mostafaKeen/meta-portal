<?php

namespace Modules\Plans\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Plans\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->paginate(10);
        return view('plans::admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('plans::admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_qr_numbers' => 'required|integer|min:-1',
            'max_agents' => 'required|integer|min:-1',
            'max_telegram_bots' => 'required|integer|min:-1',
            'max_session_messages' => 'required|integer|min:-1',
            'max_template_messages' => 'required|integer|min:-1',
            'is_active' => 'boolean',
        ]);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return view('plans::admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_qr_numbers' => 'required|integer|min:-1',
            'max_agents' => 'required|integer|min:-1',
            'max_telegram_bots' => 'required|integer|min:-1',
            'max_session_messages' => 'required|integer|min:-1',
            'max_template_messages' => 'required|integer|min:-1',
            'is_active' => 'boolean',
        ]);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        // Check if there are active subscriptions before deleting
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
