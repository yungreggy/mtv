<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Récupère trois albums aléatoires avec une image
        $albums = Album::whereNotNull('thumbnail_image')->inRandomOrder()->take(3)->get();
        return view('home', compact('albums'));
    }
}
