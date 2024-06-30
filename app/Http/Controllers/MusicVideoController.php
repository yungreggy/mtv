<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MusicVideo;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Director;
use App\Models\Genre;
use App\Models\Label;
use Illuminate\Support\Facades\Storage;


class MusicVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MusicVideo::query();

        // Filtre de recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Filtre alphabétique
        if ($request->filled('letter')) {
            $letter = $request->input('letter');
            $query->where('title', 'like', "{$letter}%");
        }

        // Filtre de tri
        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'year':
                    $query->orderBy('year', 'desc');
                    break;
                case 'date_added':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('title', 'asc');
        }

        $musicVideos = $query->with('album.artist', 'director')->paginate(30);
        $musicVideoCount = MusicVideo::count();

        return view('musicVideos.index', compact('musicVideos', 'musicVideoCount'));
    }


    public function createWithArtist($artist_id)
    {
        $albums = Album::where('artist_id', $artist_id)->pluck('title', 'id');
        $artist = Artist::findOrFail($artist_id);
        $genres = Genre::orderBy('name')->get();
        return view('musicVideos.create', compact('artist', 'albums', 'genres'));
    }

    public function createWithAlbum($albumId)
    {
        $album = Album::with('artist')->findOrFail($albumId);
        $directors = Director::orderBy('name')->get();  // Trie les réalisateurs par ordre alphabétique
        $genres = Genre::orderBy('name')->get();
        return view('musicVideos.createWithAlbum', compact('album', 'directors', 'genres'));
    }



    public function linkGenre(Request $request, $id)
    {
        $musicVideo = MusicVideo::findOrFail($id);
        $genreId = $request->input('genre_id');

        // Vérifier si le genre est déjà lié
        if (!$musicVideo->genres->contains($genreId)) {
            $musicVideo->genres()->attach($genreId);
            $genre = Genre::find($genreId);
            return response()->json(['success' => true, 'genre' => $genre]);
        }

        return response()->json(['success' => false]);
    }


    public function storeWithAlbum(Request $request, $albumId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'duration' => 'nullable|date_format:H:i:s',
            'file_path' => 'nullable|file|mimes:mp4,mkv,avi|max:20480',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'video_quality' => 'nullable|string|max:50',
            'age_rating' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'play_frequency' => 'nullable|string|max:50',
            'director_id' => 'required|integer|exists:directors,id',

            'release_date' => 'nullable|date_format:Y-m-d',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $album = Album::findOrFail($albumId);
        $artistId = $album->artist_id;

        $musicVideo = new MusicVideo();
        $musicVideo->title = $validated['title'];
        $musicVideo->album_id = $albumId;
        $musicVideo->artist_id = $artistId;  // Ajoute l'ID de l'artiste
        $musicVideo->year = $validated['year'];
        $musicVideo->duration = $validated['duration'];
        $musicVideo->video_quality = $validated['video_quality'];
        $musicVideo->age_rating = $validated['age_rating'];
        $musicVideo->language = $validated['language'];
        $musicVideo->status = $validated['status'];
        $musicVideo->tags = $validated['tags'];
        $musicVideo->play_frequency = $validated['play_frequency'];
        $musicVideo->director_id = $validated['director_id'];

        $musicVideo->release_date = $validated['release_date'];

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('musicVideos', 'public');
            $musicVideo->file_path = $filePath;
        }

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')->store('thumbnails', 'public');
            $musicVideo->thumbnail_image = $thumbnailPath;
        }

        $musicVideo->save();

        // Associer les genres de l'album aux vidéoclips
        $albumGenres = $album->genres->pluck('id')->toArray();
        $musicVideo->genres()->sync($albumGenres);

        // Associer les genres sélectionnés
        if ($request->has('genres')) {
            $musicVideo->genres()->attach($request->input('genres'));
        }

        return redirect()->route('albums.show', $albumId)->with('success', 'Vidéo musicale ajoutée avec succès.');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('musicVideos.create', compact('genres'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'duration' => 'nullable|date_format:H:i:s',
            'file_path' => 'nullable|file|mimes:mp4,mkv,avi|max:20480',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'video_quality' => 'nullable|string|max:50',
            'age_rating' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'play_frequency' => 'nullable|string|max:50',
            'artist_name' => 'required|string|max:255',
            'album_title' => 'required|string|max:255',
            'album_year' => 'required|string|max:255', // Champ string pour l'année de l'album
            'album_release_date' => 'nullable|date',
            'director_name' => 'nullable|string|max:255',
            'release_date' => 'nullable|date_format:Y-m-d',
            'genre_1' => 'nullable|string|max:255',
            'genre_2' => 'nullable|string|max:255',
            'genre_3' => 'nullable|string|max:255',
            'labels' => 'nullable|array',
            'labels.*' => 'nullable|string|max:255',
        ]);



        // Vérifier ou créer l'artiste
        $artist = Artist::firstOrCreate(
            ['name' => $validated['artist_name']],
            ['thumbnail_image' => '', 'biography' => '', 'website' => '', 'main_genre' => '', 'career_start_year' => 0, 'country_of_origin' => '']
        );

        // Vérifier ou créer l'album
        $albumData = [
            'title' => $validated['album_title'],
            'artist_id' => $artist->id,
            'year' => $validated['album_year'],
            'release_date' => $validated['album_release_date']
        ];

        $album = Album::firstOrCreate($albumData, [
            'thumbnail_image' => '',
            'description' => '',
            'track_count' => 0,
            'url' => ''
        ]);

        // Gestion de l'image de l'album
        if ($request->hasFile('thumbnail_image')) {
            $path = $request->file('thumbnail_image')->store('albums', 'public');
            $album->thumbnail_image = $path;
            $album->save(); // Save album after updating the thumbnail image path
        }

        // Vérifier ou créer le réalisateur, utiliser l'ID 6 si le nom n'est pas renseigné
        if (empty($validated['director_name'])) {
            $director = Director::find(6);
            if (!$director) {
                // Gérer le cas où l'ID 6 n'existe pas
                $director = Director::create(['name' => 'Nom par défaut']);
            }
        } else {
            $director = Director::firstOrCreate(
                ['name' => $validated['director_name']]
            );
        }

        // Créer et sauvegarder la vidéo musicale
        $musicVideo = new MusicVideo();
        $musicVideo->title = $validated['title'];
        $musicVideo->album_id = $album->id;
        $musicVideo->artist_id = $artist->id;
        $musicVideo->year = $validated['year'];
        $musicVideo->director_id = $director->id;
        $musicVideo->duration = $validated['duration'];
        $musicVideo->video_quality = $validated['video_quality'];
        $musicVideo->age_rating = $validated['age_rating'];
        $musicVideo->language = $validated['language'];
        $musicVideo->status = $validated['status'];
        $musicVideo->tags = $validated['tags'];
        $musicVideo->play_frequency = $validated['play_frequency'];
        $musicVideo->release_date = $validated['release_date'];

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('musicVideos', 'public');
            $musicVideo->file_path = $filePath;
        }

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')->store('thumbnails', 'public');
            $musicVideo->thumbnail_image = $thumbnailPath;
        }

        $musicVideo->save();

        // Associer les genres ou les créer
        $genreNames = array_filter([$validated['genre_1'], $validated['genre_2'], $validated['genre_3']]);
        $genreIds = [];

        foreach ($genreNames as $genreName) {
            $genre = Genre::firstOrCreate(['name' => $genreName, 'type' => 'music']);
            $genreIds[] = $genre->id;
        }

        if (!empty($genreIds)) {
            $album->genres()->attach($genreIds);
        }

        // Associer les genres aux clips liés à cet album
        if (!empty($genreIds)) {
            $musicVideo->genres()->attach($genreIds);
        }


        // Associer les labels
        if ($request->has('labels')) {
            foreach ($request->input('labels') as $labelName) {
                if (!empty($labelName)) {
                    $label = Label::firstOrCreate(['name' => $labelName]);
                    $album->labels()->attach($label->id);
                    $musicVideo->labels()->attach($label->id);
                }
            }
        }


        return redirect()->route('musicVideos.show', $musicVideo->id)->with('success', 'Music video ajoutée avec succès.');
    }







    /**
     * Display the specified resource.
     */
public function show($id)
{
    $musicVideo = MusicVideo::with(['album.artist', 'director', 'labels', 'genres'])->findOrFail($id);

    // Récupérer l'artiste associé à la vidéo musicale
    $artistId = $musicVideo->artist_id;

    // Trouver la vidéo musicale précédente de cet artiste
    $previousVideo = MusicVideo::where('artist_id', $artistId)
    ->where(function ($query) use ($musicVideo) {
        $query->where('year', '<', $musicVideo->year)
              ->orWhere(function ($query) use ($musicVideo) {
                  $query->where('year', $musicVideo->year)
                        ->where('title', '<', $musicVideo->title);
              });
    })
    ->orderBy('year', 'desc')
    ->orderBy('title', 'desc')
    ->first();
    // Trouver la vidéo musicale suivante de cet artiste
    $nextVideo = MusicVideo::where('artist_id', $artistId)
    ->where(function ($query) use ($musicVideo) {
        $query->where('year', '>', $musicVideo->year)
              ->orWhere(function ($query) use ($musicVideo) {
                  $query->where('year', $musicVideo->year)
                        ->where('title', '>', $musicVideo->title);
              });
    })
    ->orderBy('year', 'asc')
    ->orderBy('title', 'asc')
    ->first();
    $genres = Genre::where('type', 'music')->orderBy('name')->get();

    return view('musicVideos.show', compact('musicVideo', 'genres', 'previousVideo', 'nextVideo'));
}






    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $musicVideo = MusicVideo::findOrFail($id);
        $directors = Director::all(); // Récupère tous les réalisateurs pour le champ de sélection
        $genres = Genre::where('type', 'music')->orderBy('category')->orderBy('name')->get()->groupBy('category');
        return view('musicVideos.edit', compact('musicVideo', 'directors', 'genres'));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'duration' => 'nullable|date_format:H:i:s',
            'file_path' => 'nullable|file|mimes:mp4,mkv,avi|max:20480',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'video_quality' => 'nullable|string|max:50',
            'age_rating' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'play_frequency' => 'nullable|string|max:50',
            'director_id' => 'nullable|integer|exists:directors,id',

            'release_date' => 'nullable|date_format:Y-m-d',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $musicVideo = MusicVideo::findOrFail($id);
        $musicVideo->title = $validated['title'];
        $musicVideo->year = $validated['year'];
        $musicVideo->duration = $validated['duration'];
        $musicVideo->video_quality = $validated['video_quality'];
        $musicVideo->age_rating = $validated['age_rating'];
        $musicVideo->language = $validated['language'];
        $musicVideo->status = $validated['status'];
        $musicVideo->tags = $validated['tags'];
        $musicVideo->play_frequency = $validated['play_frequency'];
        $musicVideo->director_id = $validated['director_id'];

        $musicVideo->release_date = $validated['release_date'];

        if ($request->hasFile('file_path')) {
            // Supprimer l'ancien fichier si nécessaire
            if ($musicVideo->file_path) {
                Storage::disk('public')->delete($musicVideo->file_path);
            }
            // Enregistrer le nouveau fichier
            $filePath = $request->file('file_path')->store('musicVideos', 'public');
            $musicVideo->file_path = $filePath;
        }

        if ($request->hasFile('thumbnail_image')) {
            // Supprimer l'ancienne image si nécessaire
            if ($musicVideo->thumbnail_image) {
                Storage::disk('public')->delete($musicVideo->thumbnail_image);
            }
            // Enregistrer la nouvelle image
            $thumbnailPath = $request->file('thumbnail_image')->store('thumbnails', 'public');
            $musicVideo->thumbnail_image = $thumbnailPath;
        }

        $musicVideo->save();

        // Mise à jour des genres associés
        if ($request->has('genres')) {
            $musicVideo->genres()->sync($request->input('genres'));
        } else {
            $musicVideo->genres()->detach();
        }

        return redirect()->route('musicVideos.show', $musicVideo->id)->with('success', 'Vidéo musicale mise à jour avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $musicVideo = MusicVideo::find($id);
        $musicVideo->delete();
        return redirect()->route('musicVideos.index');
    }
}
