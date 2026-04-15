<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Company\Http\Requests\StoreUserRequest;
use Modules\Company\Http\Requests\UpdateUserRequest;

class CompanyUserController extends Controller
{
    /**
     * Display users belonging to the authenticated admin's company.
     */
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $query = User::where('company_id', $companyId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('company::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('company::users.create');
    }

    /**
     * Store a newly created user in the admin's company.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => $data['role'],
            'company_id' => $request->user()->company_id,
        ]);

        return redirect()->route('company.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Ensure the user belongs to the same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized. This user does not belong to your company.');
        }

        return view('company::users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->role  = $data['role'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('company.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized.');
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('company.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('company.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
