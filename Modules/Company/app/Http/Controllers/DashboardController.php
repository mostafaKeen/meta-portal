<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the super admin dashboard.
     */
    public function superAdmin()
    {
        return view('company::super_admin.dashboard');
    }

    /**
     * Display the company admin dashboard.
     */
    public function companyAdmin()
    {
        return view('company::company_admin.dashboard');
    }

    /**
     * Display the agent dashboard.
     */
    public function agent()
    {
        return view('company::agent.dashboard');
    }
}
