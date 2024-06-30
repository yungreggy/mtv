<?php

namespace App\Http\Controllers;

use App\Models\MusicVideo;
use App\Models\Artist;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Artist::query();

        // Gestion des filtres de tri
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'genre':
                    $query->orderBy('main_genre', 'asc');
                    break;
                case 'date_added':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // Trier par nom par défaut (A-Z)
            $query->orderBy('name', 'asc');
        }

        // Filtrer par lettre
        if ($request->has('letter')) {
            $query->where('name', 'like', $request->letter . '%');
        }

        // Barre de recherche
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $artists = $query->with('genre')->paginate(30); // Charger le genre principal associé
        $artistCount = Artist::count();
        $currentPage = $artists->currentPage(); // Récupérer le numéro de la page actuelle

        // Stocker la page actuelle dans la session
        session(['artist_page' => $currentPage]);

        return view('musicVideos.artists.index', compact('artists', 'artistCount', 'currentPage'));
    }

 

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::where('type', 'music')->orderBy('name', 'asc')->get(); // Récupère tous les genres pour les afficher dans le formulaire de création
        return view('musicVideos.artists.create', compact('genres'));
    }
        
 

    /**
     * Store a newly created resource in storage.
     */public function store(Request $request)
{
    // Validation des données entrées par l'utilisateur
    $validated = $request->validate([
        'name' => 'required|unique:artists|max:255',
        'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048', // Limite aux types MIME spécifiés et taille max de 2MB
        'biography' => 'nullable|string|max:1000',
        'website' => 'nullable|url',
        'main_genre' => 'required|exists:genres,id', // Valider que le genre existe
        'career_start_year' => 'nullable|integer',
        'country_of_origin' => 'nullable|string|max:100'
    ]);

    // Création d'une nouvelle instance de l'artiste
    $artist = new Artist();
    $artist->name = $validated['name'];
    $artist->biography = $validated['biography'];
    $artist->website = $validated['website'];
    $artist->career_start_year = $validated['career_start_year'];
    $artist->country_of_origin = $validated['country_of_origin'];

    // Gestion de l'image téléchargée
    if ($request->hasFile('thumbnail_image')) {
        $path = $request->file('thumbnail_image')->store('public/artists');
        $artist->thumbnail_image = $path;
    }

    // Sauvegarde de l'artiste dans la base de données
    $artist->save();

    // Associer le genre principal
    $artist->genres()->attach($validated['main_genre']);
    $this->cascadeGenreToAlbumsAndVideos($artist, $validated['main_genre']);

    // Redirection vers la page de l'artiste nouvellement créé avec un message de succès
    return redirect()->route('artists.show', $artist->id)->with('success', 'Artiste ajouté avec succès.');
}

protected function cascadeGenreToAlbumsAndVideos(Artist $artist, $genreId)
{
    foreach ($artist->albums as $album) {
        $album->genres()->syncWithoutDetaching($genreId);
        foreach ($album->musicVideos as $musicVideo) {
            $musicVideo->genres()->syncWithoutDetaching($genreId);
        }
    }
}

public function uploadThumbnail(Request $request, $id)
{
    $request->validate([
        'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $artist = Artist::findOrFail($id);

    if ($request->hasFile('thumbnail_image')) {
        $file = $request->file('thumbnail_image');
        $path = $file->store('thumbnails', 'public');

        // Supprimer l'ancienne image si elle existe
        if ($artist->thumbnail_image) {
            Storage::disk('public')->delete($artist->thumbnail_image);
        }

        // Mettre à jour le chemin de l'image
        $artist->thumbnail_image = $path;
        $artist->save();
    }

    return response()->json(['success' => true, 'message' => 'Image téléversée avec succès']);
}

    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $artist = Artist::with([
            'albums' => function ($query) {
                $query->orderBy('year', 'desc');
            },
            'musicVideos' => function ($query) {
                $query->orderBy('year', 'desc');
            },
            'genres' // Inclure les genres pour l'affichage
        ])->findOrFail($id);
    
        $genres = Genre::orderBy('name', 'asc')->get(); // Récupère tous les genres triés par nom
    
        return view('musicVideos.artists.show', compact('artist', 'genres'));
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $artist = Artist::findOrFail($id);
        $genres = Genre::where('type', 'music')->orderBy('name', 'asc')->get(); // Récupérer uniquement les genres de type 'music' en ordre alphabétique
        return view('musicVideos.artists.edit', compact('artist', 'genres'));
    }
    

    /**
     * Update the specified resource in storage.
     */
   
     public function update(Request $request, $id)
     {
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
             'biography' => 'nullable|string|max:1000',
             'website' => 'nullable|url',
             'main_genre' => 'required|string|max:100',
             'career_start_year' => 'nullable|integer',
             'country_of_origin' => 'string|max:100'
         ]);
     
         $artist = Artist::findOrFail($id);
         $artist->name = $validated['name'];
         $artist->biography = $validated['biography'];
         $artist->website = $validated['website'];
         $artist->main_genre = $validated['main_genre'];
         $artist->career_start_year = $validated['career_start_year'];
         $artist->country_of_origin = $validated['country_of_origin'];
     
         if ($request->hasFile('thumbnail_image')) {
             $path = $request->file('thumbnail_image')->store('artists', 'public');
             $artist->thumbnail_image = $path;
         }
     
         $artist->save();
     
         // Gérer la propagation du genre principal aux albums et vidéoclips
         $genre = Genre::firstOrCreate(['name' => $validated['main_genre']]);
         $artist->genres()->sync([$genre->id]);
     
         // Ajouter le genre à tous les albums de l'artiste
         foreach ($artist->albums as $album) {
             $album->genres()->syncWithoutDetaching([$genre->id]);
     
             // Ajouter le genre à tous les vidéoclips de l'album
             foreach ($album->musicVideos as $musicVideo) {
                 $musicVideo->genres()->syncWithoutDetaching([$genre->id]);
             }
         }
     
         return redirect()->route('artists.show', $artist->id)->with('success', 'Artiste mis à jour avec succès.');
     }
     
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $artist = Artist::findOrFail($id);
    
            // Supprimer tous les albums de l'artiste et les vidéos musicales liées
            foreach ($artist->albums as $album) {
                foreach ($album->musicVideos as $musicVideo) {
                    // Détacher les genres associés à la vidéo musicale
                    $musicVideo->genres()->detach();
                    // Supprimer la vidéo musicale
                    $musicVideo->delete();
                }
                // Supprimer l'album
                $album->delete();
            }
    
            // Détacher les genres associés à l'artiste
            $artist->genres()->detach();
    
            // Supprimer l'artiste
            $artist->delete();
    
            DB::commit();
            return redirect()->route('artists.index')->with('success', 'L\'artiste et tous ses albums et vidéos musicales ont été supprimés avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('artists.index')->with('error', 'Une erreur est survenue lors de la suppression de l\'artiste.');
        }
    }

    public function updateMainGenre(Request $request, $id)
{
    $request->validate([
        'main_genre' => 'required|string|exists:genres,name',
    ]);

    $artist = Artist::findOrFail($id);
    $artist->main_genre = $request->input('main_genre');
    $artist->save();

    return redirect()->route('artists.show', $artist->id)->with('success', 'Main Genre updated successfully.');
}

    
}
