<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\application;
use App\Models\Message;
use App\Models\pets;
use App\Models\usersdata;
use Illuminate\Support\Facades\Storage;

class mylistingcontroller extends Controller
{
    function index(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $pets = pets::where('user_id', $user->id)
            ->withCount('applications')
            ->latest('id')
            ->get();

        return view('mylisting', ['pets' => $pets, 'user' => $user]);
    }

    function applications(Request $request, $id)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $pet = pets::where('user_id', $user->id)->findOrFail($id);
        $applications = application::where('pet_id', $pet->id)
            ->with('user')
            ->latest('id')
            ->get();

        return view('listing_applications', [
            'user' => $user,
            'pet' => $pet,
            'applications' => $applications,
        ]);
    }

    function edit(Request $request, $id)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $pet = pets::where('user_id', $user->id)->findOrFail($id);

        return view('editlisting', [
            'user' => $user,
            'pet' => $pet,
        ]);
    }

    function update(Request $request, $id)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $pet = pets::where('user_id', $user->id)->findOrFail($id);

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

        $imageUrl = $pet->image_url;
        if ($request->hasFile('Photos')) {
            if ($imageUrl && str_starts_with($imageUrl, '/storage/')) {
                Storage::disk('public')->delete(substr($imageUrl, strlen('/storage/')));
            }

            $path = $request->file('Photos')->store('pets', 'public');
            $imageUrl = Storage::url($path);
        }

        $pet->update([
            'name' => $data['petname'],
            'breed' => $data['breed'],
            'age' => $data['age'],
            'description' => $data['Description'] ?? null,
            'gender' => $data['gender'],
            'size' => $data['size'],
            'city' => $data['Location'],
            'image_url' => $imageUrl,
        ]);

        return redirect('/mylisting')->with('success', 'Pet listing updated successfully.');
    }

    function updateApplicationStatus(Request $request, $petId, $applicationId)
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected,pending'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $pet = pets::where('user_id', $user->id)->findOrFail($petId);
        $application = application::where('pet_id', $pet->id)->findOrFail($applicationId);
        $application->status = $data['status'];
        $application->save();

        if ($data['status'] === 'approved') {
            $pet->adopted = true;
            $pet->save();

            application::where('pet_id', $pet->id)
                ->where('id', '!=', $application->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        }

        $statusSentence = match ($data['status']) {
            'approved' => 'Your application has been approved.',
            'rejected' => 'Your application has been rejected.',
            default => 'Your application status has been updated to pending.',
        };

        $message = $statusSentence.' Pet: '.$pet->name.'.';
        if (!empty($data['note'])) {
            $message .= ' Note: '.$data['note'];
        }

        Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $application->user_id,
            'pet_id' => $pet->id,
            'body' => $message,
        ]);

        return back()->with('success', 'Application status updated.');
    }

    function markAdopted(Request $request, $id)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $pet = pets::where('user_id', $user->id)->findOrFail($id);
        $pet->adopted = true;
        $pet->save();

        return back()->with('success', 'Pet marked as adopted.');
    }
}
