<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Modules\Company\Models\Company;
use Modules\Company\Http\Requests\StoreCompanyRequest;
use Modules\Company\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies (Super Admin).
     */
    public function index(Request $request)
    {
        $query = Company::query()->withCount('users');

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('domain_slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        return view('company::companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        return view('company::companies.create');
    }

    /**
     * Store a newly created company.
     */
    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            // Handle logo upload
            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
            }

            $company = Company::create($data);

            // Create Primary Admin User
            $admin = new User([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'company_id' => $company->id,
                'role' => 'company_admin',
            ]);
            $admin->plainPassword = $data['admin_password'];
            $admin->save();

            DB::commit();

            return redirect()->route('company.index')
                ->with('success', 'Company and Admin created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating company: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified company.
     */
    public function show(Company $company)
    {
        $company->loadCount('users');
        $users = $company->users()->latest()->paginate(10);

        return view('company::companies.show', compact('company', 'users'));
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company)
    {
        $admin = $company->users()->where('role', 'company_admin')->first();
        return view('company::companies.edit', compact('company', 'admin'));
    }

    /**
     * Update the specified company.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($company->logo) {
                    Storage::disk('public')->delete($company->logo);
                }
                $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
            }

            $company->update($data);

            // Update Primary Admin User
            $admin = $company->users()->where('role', 'company_admin')->first();
            
            $userData = [
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
            ];

            if (!empty($data['admin_password'])) {
                $userData['password'] = Hash::make($data['admin_password']);
            }

            if ($admin) {
                // We don't necessarily send a welcome email on update unless password changed and we want to notify
                // But the requirement says "any user added", so we focus on creation.
                $admin->update($userData);
            } else {
                // Creation fallback if no admin exists for some reason
                $admin = new User(array_merge($userData, [
                    'company_id' => $company->id,
                    'role' => 'company_admin',
                    'password' => Hash::make($data['admin_password'] ?? 'password'),
                ]));
                $admin->plainPassword = $data['admin_password'] ?? 'password';
                $admin->save();
            }

            DB::commit();

            return redirect()->route('company.index')
                ->with('success', 'Company updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating company: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified company.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('company.index')
            ->with('success', 'Company deleted successfully.');
    }
}
