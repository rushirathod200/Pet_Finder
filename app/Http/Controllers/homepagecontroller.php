<?php

namespace App\Http\Controllers;

use App\Models\pets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class homepagecontroller extends Controller
{
    function index()
    {
        $featuredPets = collect();
        if (Schema::hasTable('pets')) {
            $featuredPets = pets::where('adopted', false)
                ->latest('id')
                ->take(10)
                ->get();
        }

        return view('homepage', [
            'featuredPets' => $featuredPets,
        ]);
    }

    function howItWorks()
    {
        return view('howitworks');
    }
}
