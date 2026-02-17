<?php

namespace App\Http\Controllers;

use App\Models\pets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class homepagecontroller extends Controller
{
    function index()
    {
        $featuredPets = collect();
        try {
            if (Schema::hasTable('pets')) {
                $featuredPets = pets::where('adopted', false)
                    ->latest('id')
                    ->take(10)
                    ->get();
            }
        } catch (Throwable $e) {
            report($e);
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
