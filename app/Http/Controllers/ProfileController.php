<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasAnyRole([\App\Models\Role::EMPLOYEE, \App\Models\Role::ADMIN])) {
             $user->load('employee');
        }
        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasAnyRole([\App\Models\Role::EMPLOYEE, \App\Models\Role::ADMIN])) {
             $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'phone' => ['required', 'string', 'max:20'],
                'address' => ['required', 'string', 'max:255'],
            ]);

            $user->update([
                'email' => $request->email,
            ]);

            $user->employee()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]
            );
        } else {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ]);
    
            $user->update([
                'email' => $request->email,
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
