<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pets;
use App\Models\application;
use App\Models\usersdata;
use App\Models\Message;

class petdetilscontroller extends Controller
{
    function index($id)
    {
        $pet = pets::with('owner')->findOrFail($id);

        return view('petdetails', ['pet' => $pet]);
    }

    function apply(Request $request, $id)
    {
        $request->validate([
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $pet = pets::findOrFail($id);
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();

        if (!$user) {
            return redirect('/login');
        }

        if ($pet->adopted) {
            return back()->with('error', 'This pet is already adopted.');
        }

        if ($pet->user_id === $user->id) {
            return back()->with('error', 'You cannot apply for your own listing.');
        }

        $existing = application::where('user_id', $user->id)
            ->where('pet_id', $pet->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already applied for this pet.');
        }

        application::create([
            'status' => 'pending',
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'description' => $request->input('description'),
        ]);

        Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $pet->user_id,
            'pet_id' => $pet->id,
            'body' => $request->filled('description')
                ? 'New application submitted: '.$request->input('description')
                : 'New application submitted for '.$pet->name.'.',
        ]);

        return redirect('/myapplication')->with('success', 'Application submitted successfully.');
    }
}
