<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Regist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function regist(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'name' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = new User($validated);
        try {
            $result = $user->save();
        } catch (Exception $e) {
            $message = 'Failed to regist user';
            $error = $e->getMessage();
        }

        return [
            'result' => $result ? 'success' : 'failure',
            'id' => $user->id,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ];
    }

    /**
     * Login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $result = false;
        if (Auth::attempt($validated)) {
            // Authentication passed...
            $token = $request->user()->createToken(
                Hash::make($request->user()->id . '-' . time()),
                [
                    User::CAN_CREATE_STORE,
                    User::CAN_UPDATE_STORE,
                    User::CAN_CREATE_PRODUCT,
                    User::CAN_UPDATE_PRODUCT,
                ]
            );
            $result = true;
        }

        return [
            'result' => $result ? 'success' : 'failure',
            'token' => $token ?? null,
        ];
    }

    /**
     * Logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request...
            $result = $request->user()->currentAccessToken()->delete();
        } catch (Exception $e) {
            $message = 'Failed to logout user';
            $error = $e->getMessage();
        }

        return [
            'result' => $result ? 'success' : 'failure',
            'message' => $message ?? null,
            'error' => $error ?? null,
        ];
    }
}
