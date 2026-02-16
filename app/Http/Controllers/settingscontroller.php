<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usersdata;
use Illuminate\Support\Facades\Hash;

class settingscontroller extends Controller
{
    function index(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        return view('settings', ['user' => $user]);
    }

    function updateProfile(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usersdatas,email,'.$user->id],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user->update($data);
        $request->session()->put('useremail', $data['email']);

        return back()->with('success', 'Profile updated successfully.');
    }

    function updatePassword(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
