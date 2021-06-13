<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handles login request from user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request for username and password
        $login = $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);

        // Handle invalid auth
        if(! Auth::attempt($login) ) {
            return response(['message' => 'Invalid login credentials'], 401);
        }

        // If login is successful, then generate personal access token
        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        // Return accessToken in response
        return response(['user' => Auth::user(), 'accessToken' => $accessToken]);

    }
}
