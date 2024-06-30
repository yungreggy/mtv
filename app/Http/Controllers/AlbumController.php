<?php

namespace App\Http\Controllers;
use App\Models\Album;
use App\Models\Label;
use App\Models\Genre;
use Illuminate\Http\Request;


class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Album::query();
        
        // Gestion des filtres de tri
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'artist_asc':
                    $query->join('artists', 'albums.artist_id', '=', 'artists.id')
                          ->orderBy('artists.name', 'asc')
                          ->select('albums.*'); // Sélectionner les colonnes de l'album après le join
                    break;
                case 'artist_desc':
                    $query->join('artists', 'albums.artist_id', '=', 'artists.id')
                          ->orderBy('artists.name', 'desc')
                          ->select('albums.*');
                    break;
                case 'year_asc':
                    $query->orderBy('year', 'asc');
                    break;
                case 'year_desc':
                    $query->orderBy('year', 'desc');
                    break;
            }
        } else {
            // Trier par titre par défaut (A-Z)
            $query->orderBy('title', 'asc');
        }
        
        $albums = $query->paginate(30); // Paginer à 30 éléments par page
        $albumCount = Album::count(); // Calculer le nombre total d'albums
        return view('musicVideos.albums.index', compact('albums', 'albumCount'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labels = Label::orderBy('name', 'asc')->get();
        $genres = Genre::orderBy('name', 'asc')->get();
        return view('musicVideos.albums.create', compact('labels', 'genres'));
    }
    
    public function createWithArtist($artist_id)
    {
        $labels = Label::orderBy('name', 'asc')->get();
        $genres = Genre::orderBy('name', 'asc')->get();
        return view('musicVideos.albums.create', compact('artist_id', 'labels', 'genres'));
    }
    
    public function uploadThumbnail(Request $request, Album $album)
    {
        try {
            $request->validate([
                'thumbnail_image' => 'required|image|mimes:jpeg,png,gif|max:2048',
            ]);
    
            if ($request->hasFile('thumbnail_image')) {
                $path = $request->file('thumbnail_image')->store('albums', 'public');
                $album->thumbnail_image = $path;
                $album->save();
    
                return response()->json(['success' => true, 'message' => 'Image uploaded successfully']);
            }
    
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        } catch (Exception $e) {
            \Log::error('Upload Thumbnail Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    protected function syncMusicVideoGenres(Album $album)
{
    $genres = $album->genres;

    foreach ($album->musicVideos as $musicVideo) {
        $musicVideo->genres()->syncWithoutDetaching($genres->pluck('id')->toArray());
    }
}

    




    public function store(Request $request)
{
    // Validation des données entrées par l'utilisateur
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'year' => 'required|integer',
        'artist_id' => 'nullable|integer|exists:artists,id',
        'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        'description' => 'nullable|string',
        'track_count' => 'nullable|integer',
        'release_date' => 'nullable|date',
        'url' => 'nullable|url',
        'genres' => 'nullable|array',
        'genres.*' => 'exists:genres,id',
        'labels' => 'nullable|array',
        'labels.*' => 'nullable|string|max:255',
    ]);

    // Création d'une nouvelle instance de l'album
    $album = new Album();
    $album->title = $validated['title'];
    $album->year = $validated['year'];
    $album->artist_id = $request->artist_id;
    $album->description = $validated['description'];
    $album->track_count = $validated['track_count'];
    $album->release_date = $validated['release_date'];
    $album->url = $validated['url'];

    // Gestion de l'image téléchargée
    if ($request->hasFile('thumbnail_image')) {
        $path = $request->file('thumbnail_image')->store('albums', 'public');
        $album->thumbnail_image = $path;
    }

    // Sauvegarde de l'album dans la base de données
    $album->save();

    // Associer les genres sélectionnés
    if ($request->has('genres')) {
        $album->genres()->attach($request->input('genres'));
    }

    // Gestion des labels
    if ($request->has('labels')) {
        foreach ($request->input('labels') as $labelName) {
            if (!empty($labelName)) {
                $label = Label::firstOrCreate(['name' => $labelName]);
                $album->labels()->attach($label->id);
                // Ajouter les labels aux vidéoclips associés
                foreach ($album->musicVideos as $musicVideo) {
                    $musicVideo->labels()->attach($label->id);
                }
            }
        }
    }

    // Redirection vers la page de l'artiste avec un message de succès
    return redirect()->route('artists.show', ['artist' => $album->artist_id])->with('success', 'Album ajouté avec succès.');
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $album = Album::with([
            'musicVideos' => function ($query) {
                $query->orderBy('year', 'desc');
            },
            'genres'
        ])->findOrFail($id);

        $nextAlbum = Album::where('artist_id', $album->artist_id)
        ->where('year', '>', $album->year)
        ->orderBy('year', 'asc')
        ->first();
    

    
        $genres = Genre::where('type', 'music')->orderBy('name', 'asc')->get();
    
        return view('musicVideos.albums.show', compact('album', 'genres', 'nextAlbum'));
    }
    
    public function linkGenre(Request $request, $albumId)
    {
        $validated = $request->validate([
            'genre_id' => 'required|exists:genres,id',
        ]);
    
        $album = Album::findOrFail($albumId);
        $genreId = $validated['genre_id'];
    
        if (!$album->genres->contains($genreId)) {
            $album->genres()->attach($genreId);
    
            // Ajouter le genre aux music_videos associés
            foreach ($album->musicVideos as $musicVideo) {
                if (!$musicVideo->genres->contains($genreId)) {
                    $musicVideo->genres()->attach($genreId);
                }
            }
    
            return response()->json([
                'success' => true,
                'genre' => Genre::findOrFail($genreId),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Genre déjà lié à cet album.',
            ]);
        }
    }
    

    


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $album = Album::findOrFail($id);
        $labels = Label::orderBy('name', 'asc')->get();
        $genres = Genre::orderBy('name', 'asc')->get(); // Récupérer les genres en ordre alphabétique
    
        return view('musicVideos.albums.edit', compact('album', 'labels', 'genres'));
    }
    
    
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Validation des données entrées par l'utilisateur
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'year' => 'required|integer',
        'artist_id' => 'nullable|integer|exists:artists,id',
        'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        'description' => 'nullable|string',
        'track_count' => 'nullable|integer',
        'release_date' => 'nullable|date',
        'url' => 'nullable|url',
        'genres' => 'nullable|array',
        'genres.*' => 'exists:genres,id',
        'labels' => 'nullable|array',
        'labels.*' => 'nullable|string|max:255',
    ]);

    $album = Album::findOrFail($id);
    $album->title = $validated['title'];
    $album->year = $validated['year'];
    $album->artist_id = $request->artist_id;
    $album->description = $validated['description'];
    $album->track_count = $validated['track_count'];
    $album->release_date = $validated['release_date'];
    $album->url = $validated['url'];

    // Gestion de l'image téléchargée
    if ($request->hasFile('thumbnail_image')) {
        $path = $request->file('thumbnail_image')->store('albums', 'public');
        $album->thumbnail_image = $path;
    }

    // Sauvegarde de l'album dans la base de données
    $album->save();

    if ($request->has('genres')) {
        $album->genres()->sync($request->input('genres'));

        // Mettre à jour les genres des music_videos associés à cet album
        $this->syncMusicVideoGenres($album);
    } else {
        $album->genres()->detach();
    }

    // Mettre à jour les associations de labels
    if ($request->has('labels')) {
        $labelIds = [];
        foreach ($request->input('labels') as $labelName) {
            if (!empty($labelName)) {
                $label = Label::firstOrCreate(['name' => $labelName]);
                $labelIds[] = $label->id;
            }
        }
        $album->labels()->sync($labelIds);

        // Ajouter les labels aux vidéoclips associés
        foreach ($album->musicVideos as $musicVideo) {
            $musicVideo->labels()->sync($labelIds);
        }
    } else {
        $album->labels()->detach();

        // Supprimer les labels des vidéoclips associés
        foreach ($album->musicVideos as $musicVideo) {
            $musicVideo->labels()->detach();
        }
    }

    // Redirection vers la page de l'album avec un message de succès
    return redirect()->route('albums.show', ['album' => $album->id])->with('success', 'Album mis à jour avec succès.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
