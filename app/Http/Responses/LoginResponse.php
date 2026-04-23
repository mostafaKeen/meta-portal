<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return redirect()->intended(route('admin.dashboard', ['verified' => 1]));
        }

        if ($user->isCompanyAdmin()) {
            return redirect()->intended(route('company.dashboard', ['verified' => 1]));
        }

        if ($user->isAgent()) {
            return redirect()->intended(route('agent.dashboard', ['verified' => 1]));
        }

        // Default redirect
        return redirect()->intended(config('fortify.home') . '?verified=1');
    }
}
