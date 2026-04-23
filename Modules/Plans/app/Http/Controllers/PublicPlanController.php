<?php

namespace Modules\Plans\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Plans\Models\Plan;

class PublicPlanController extends Controller
{
    /**
     * Display a public listing of active plans.
     */
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('plans::public.index', compact('plans'));
    }
}
