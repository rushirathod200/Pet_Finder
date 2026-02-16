<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pets;

class allpetcontroller extends Controller
{
    function index(Request $request)
    {
        $query = pets::query()->where('adopted', false);

        if ($request->filled('q')) {
            $term = $request->string('q');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('breed', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%");
            });
        }

        $pets = $query->latest('id')->paginate(12)->withQueryString();

        return view('allpet', ['pets' => $pets]);
    }
}
