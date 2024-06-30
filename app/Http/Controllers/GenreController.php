<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $search = $request->get('search');
    
        $query = Genre::query();
    
        if ($type && $type !== '') {
            $query->where('type', $type);
        }
    
        if ($search && $search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }
    
        $genres = $query->orderBy('name', 'asc')->paginate(30);
    
        return view('genres.index', compact('genres', 'type', 'search'));
    }
    
    
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('genres.create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:music,film',
        ]);

        // Création du genre
        $genre = new Genre();
        $genre->name = $request->input('name');
        $genre->type = $request->input('type');
        $genre->save();

        // Redirection avec un message de succès
        return redirect()->route('genres.index')->with('success', 'Le genre a été créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $genre = Genre::with(['albums', 'musicVideos', 'artists', 'tvShows', 'films'])->findOrFail($id);
    
        // Récupérer le genre suivant
        $nextGenre = Genre::where('type', $genre->type)
                          ->where('name', '>', $genre->name)
                          ->orderBy('name', 'asc')
                          ->first();
    
        return view('genres.show', compact('genre', 'nextGenre'));
    }
    
    
    
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $genres = Genre::find($id);
        return view('genres.edit', compact('genres'));
    }

    /**
     * Update the specified resource in storage.
     */
   /**
 * Update the specified resource in storage.
 */
public function update(Request $request, Genre $genre)
{
    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:music,film',
    ]);

    // Mise à jour du genre
    $genre->name = $request->input('name');
    $genre->type = $request->input('type');
    $genre->save();

    // Redirection avec un message de succès
    return redirect()->route('genres.index')->with('success', 'Le genre a été mis à jour avec succès.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        // Détacher les relations
        $genre->artists()->detach();
        $genre->albums()->detach();
        $genre->films()->detach();
        $genre->tvShows()->detach();
        $genre->musicVideos()->detach();
    
        // Supprimer le genre
        $genre->delete();
    
        // Rediriger avec un message de succès
        return redirect()->route('genres.index')->with('success', 'Genre supprimé avec succès.');
    }
    
}
