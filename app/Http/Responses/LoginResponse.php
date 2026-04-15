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
            return redirect()->intended(route('company.index'));
        }

        if ($user->isCompanyAdmin()) {
            return redirect()->intended(route('company.users.index'));
        }

        // Default redirect for agents or other roles
        return redirect()->intended(config('fortify.home'));
    }
}
