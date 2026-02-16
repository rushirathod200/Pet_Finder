<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pets;
use App\Models\usersdata;
use Illuminate\Support\Facades\Storage;

class listapetcontroller extends Controller
{
    function index(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        return view('listapet', ['user' => $user]);
    }

    function storepet(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $data = $request->validate([
            'petname' => ['required', 'string', 'max:255'],
            'breed' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:0', 'max:40'],
            'Description' => ['nullable', 'string', 'max:2000'],
            'gender' => ['required', 'in:Male,Female'],
            'size' => ['required', 'string', 'max:50'],
            'Location' => ['required', 'string', 'max:255'],
            'Photos' => ['nullable', 'image', 'max:5120'],
        ]);

        $imageUrl = null;
        if ($request->hasFile('Photos')) {
            $path = $request->file('Photos')->store('pets', 'public');
            $imageUrl = Storage::url($path);
        }

        pets::create([
            'name' => $data['petname'],
            'breed' => $data['breed'],
            'age' => $data['age'],
            'description' => $data['Description'] ?? null,
            'gender' => $data['gender'],
            'size' => $data['size'],
            'image_url' => $imageUrl,
            'city' => $data['Location'],
            'user_id' => $user->id,
        ]);

        return redirect('/mylisting')->with('success', 'Pet listed successfully.');
    }
}
