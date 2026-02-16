<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\application;
use App\Models\usersdata;

class myapplicationcontroller extends Controller
{
    function index(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $applications = application::where('user_id', $user->id)
            ->with(['pet.owner'])
            ->latest('id')
            ->get();

        return view('myapplication', ['applications' => $applications, 'user' => $user]);
    }
}
