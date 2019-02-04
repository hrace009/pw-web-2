<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.password');
    }

    /**
     * Update user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(PasswordRequest $request)
    {
        $user = auth()->user();

        // Check the current password
        if (! hash_equals(hash_password($user->name.$request->current_password), $user->passwd)) {
            flash('Your current password seems to be invalid.')->error();

            return redirect()->route('profile.settings');
        }

        // Hash the new password in pw-style
        $hashPassword = Hash::make($user->name.$request->password);

        // Update user's password
        tap($user)->update([
            'passwd' => $hashPassword,
        ]);

        flash('Your password was successfully updated.')->success();

        return redirect()->route('profile.settings');
    }
}
