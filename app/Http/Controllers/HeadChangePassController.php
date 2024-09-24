<?php

namespace App\Http\Controllers;

use App\Models\Users; // Use your Users model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HeadChangePassController extends Controller
{
    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['You need to log in first.']);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Users::find(Auth::id()); // Use the Users model

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save(); // Save the updated user

        return back()->with('success', 'Password changed successfully.');
    }
}
