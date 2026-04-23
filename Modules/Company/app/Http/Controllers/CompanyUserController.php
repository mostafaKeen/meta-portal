<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Company\Http\Requests\StoreUserRequest;
use Modules\Company\Http\Requests\UpdateUserRequest;
use Modules\Company\Models\WhatsappNumber;

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
        $company = $request->user()->company;

        if ($company->hasReachedAgentLimit()) {
            return redirect()->back()
                ->with('error', 'You have reached the maximum number of agents allowed for your plan.')
                ->withInput();
        }

        $data = $request->validated();

        $user = new User([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => $data['role'],
            'company_id' => $company->id,
        ]);
        $user->plainPassword = $data['password'];
        $user->save();

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

        $whatsappNumbers = WhatsappNumber::where('company_id', auth()->user()->company_id)->get();
        $assignedNumbers = $user->whatsappNumbers()->pluck('access_type', 'whatsapp_number_id')->toArray();

        return view('company::users.edit', compact('user', 'whatsappNumbers', 'assignedNumbers'));
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

        if ($user->isAgent() && $request->has('whatsapp_numbers')) {
            $syncData = [];
            foreach ($request->input('whatsapp_numbers', []) as $numberId => $data) {
                if (isset($data['assigned']) && $data['assigned'] == '1') {
                    $syncData[$numberId] = ['access_type' => $data['access_type'] ?? 'view'];
                }
            }
            $user->whatsappNumbers()->sync($syncData);
        } else {
            $user->whatsappNumbers()->detach();
        }

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
