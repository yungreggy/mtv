<?php

// app/Http/Controllers/FilmController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Director;
use App\Models\Label;
use App\Models\Tag;
use App\Models\Genre;

use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    public function index(Request $request)
{
    $query = Film::query();

    if ($request->has('search')) {
        $search = $request->input('search');
        $year = $search;

        $query->where(function ($q) use ($search, $year) {
            $q->where('title', 'LIKE', "%$search%")
              ->orWhere('year', 'LIKE', "%$year%")
              ->orWhereHas('director', function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%$search%");
              })
              ->orWhereHas('genres', function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%$search%");
              })
              ->orWhereHas('tags', function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%$search%");
              });
        });
    }

    if ($request->has('sort_by')) {
        $sort_by = $request->input('sort_by');
        switch ($sort_by) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'director':
                $query->orderBy('director_id');
                break;
            case 'year':
                $query->orderBy('year', 'asc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
        }
    } else {
        $query->orderBy('created_at', 'desc');
    }

    $films = $query->paginate(30);

    return view('films.index', compact('films'));
}

    
// FilmController.php
public function list()
    {
        $films = Film::orderBy('title', 'asc')->get();
        return response()->json($films);
    }


    

    public function create()
    {
        $directors = Director::all();
        $labels = Label::all();
        $filmGenres = Genre::where('type', 'film')->orderBy('name', 'asc')->get();
        return view('films.create', compact('directors', 'labels', 'filmGenres'));
    }


    public function edit(Film $film)
    {
        $filmGenres = Genre::where('type', 'film')->orderBy('name', 'asc')->get();
    
        // Récupérer le film précédent
        $previousFilm = Film::where('id', '<', $film->id)->orderBy('id', 'desc')->first();
        // Récupérer le film suivant
        $nextFilm = Film::where('id', '>', $film->id)->orderBy('id', 'asc')->first();
    
        return view('films.edit', compact('film', 'filmGenres', 'previousFilm', 'nextFilm'));
    }
    

 
    
    
    
    


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'director_name' => 'required|string|max:255',
            'label_id' => 'nullable|exists:labels,id',
            'duration' => 'required|date_format:H:i',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file|max:2048',
            'local_image_path' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_poster' => 'nullable|string|max:255',
            'rating' => 'nullable|string|max:10',
            'primary_language' => 'required|string|max:50',
            'country_of_origin' => 'nullable|string|max:100',
            'production_company' => 'nullable|string|max:255',
            'distributor' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'tags' => 'nullable|string',
        ]);
    
        // Vérifier ou créer le réalisateur
        $director = Director::firstOrCreate(['name' => $validated['director_name']]);
        
        // Créer le film avec les données validées et l'ID du réalisateur
        $film = new Film($validated);
        $film->director_id = $director->id;
    
        if ($request->hasFile('local_image_path') && $request->file('local_image_path')->isValid()) {
            $film->local_image_path = $request->file('local_image_path')->store('posters', 'public');
        }
    
        if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
            $film->file_path = $request->file('file_path')->store('files', 'public');
        }
    
        $film->save();
    
        // Attacher les genres au film
        if (isset($validated['genres'])) {
            $film->genres()->attach($validated['genres']);
        }

         // Ajouter les tags
    if (!empty($validated['tags'])) {
        $tags = array_map('trim', explode(',', $validated['tags']));
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $film->tags()->attach($tag->id);
        }
    }
    
        return redirect()->route('films.index')->with('success', 'Film ajouté avec succès');
    }
    
    


    public function linkGenre(Request $request, $id)
{
    $film = Film::findOrFail($id);
    $genreId = $request->input('genre_id');

    // Vérifiez si le genre est de type 'film'
    $genre = Genre::findOrFail($genreId);
    if ($genre->type !== 'film') {
        return response()->json(['success' => false, 'message' => 'Invalid genre type.']);
    }

    // Associez le genre au film
    $film->genres()->attach($genreId);

    return response()->json(['success' => true, 'genre' => $genre]);
}

    
    

public function uploadFilm(Request $request)
{
    if ($request->hasFile('film')) {
        $fileName = $request->file('film')->getClientOriginalName();
        $filePath = Storage::disk('films')->putFileAs('', $request->file('film'), $fileName);

        // Sauvegarde uniquement du nom du fichier dans la base de données
        $film = Film::create([
            'title' => $request->input('title'),
            'file_path' => $fileName,
        ]);

        return redirect()->route('films.show', $film->id);
    }

    return back()->with('error', 'No file uploaded');
}

public function getFilm($id)
{
    $film = Film::findOrFail($id);

    if (!Storage::disk('films')->exists($film->file_path)) {
        abort(404);
    }

    return response()->file(Storage::disk('films')->path($film->file_path));
}

public function show(Film $film)
{
    $genres = Genre::where('type', 'film')->orderBy('name', 'asc')->get();
    return view('films.show', compact('film', 'genres'));
}

public function update(Request $request, $id)
{
    $film = Film::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'year' => 'required|integer',
        'director_name' => 'required|string|max:255', // Remplacer director_id par director_name
        'description' => 'nullable|string',
        'duration' => 'required|date_format:H:i',
        'file_path' => 'nullable|string|max:255',
        'local_image_path' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'rating' => 'nullable|string|max:10',
        'primary_language' => 'required|string|max:50',
        'country_of_origin' => 'nullable|string|max:100',
        'production_company' => 'nullable|string|max:255',
        'distributor' => 'nullable|string|max:255',
        'genres' => 'nullable|array',
        'genres.*' => 'exists:genres,id',
    ]);

    // Vérifier ou créer le réalisateur
    $director = Director::firstOrCreate(['name' => $validated['director_name']]);

    $film->title = $validated['title'];
    $film->year = $validated['year'];
    $film->director_id = $director->id; // Associer l'ID du réalisateur au film
    $film->description = $validated['description'];
    $film->duration = $validated['duration'];
    $film->rating = $validated['rating'];
    $film->primary_language = $validated['primary_language'];
    $film->country_of_origin = $validated['country_of_origin'];
    $film->production_company = $validated['production_company']; // Ajouter la compagnie de production
    $film->distributor = $validated['distributor']; // Ajouter le distributeur

    if (!empty($validated['file_path'])) {
        $film->file_path = $validated['file_path'];
    }

    if ($request->hasFile('local_image_path')) {
        $film->local_image_path = $request->file('local_image_path')->store('posters', 'public');
    }

    $film->save();

    // Synchroniser les genres du film
    if (isset($validated['genres'])) {
        $film->genres()->sync($validated['genres']);
    }

    return redirect()->route('films.edit', $film->id)->with('success', 'Film mis à jour avec succès');

}


    






}
