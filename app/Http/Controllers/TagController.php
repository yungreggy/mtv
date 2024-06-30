<?php

namespace App\Http\Controllers;

use App\Models\Tag;

use App\Models\Film;
use App\Models\TvShowsEpisode;
use App\Models\Pub;
use Illuminate\Http\Request;


class TagController extends Controller
{
    public function index(Request $request)
    {
        $query = Tag::query();
    
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }
    
        if ($request->has('sort_by')) {
            switch ($request->input('sort_by')) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }
    
        $tags = $query->paginate(100); 
    
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        Tag::create($request->all());

        return redirect()->route('tags.index')->with('success', 'Tag créé avec succès.');
    }


    public function show($id)
    {
        $tag = Tag::with(['films' => function($query) {
            $query->orderBy('title', 'asc');
        }])->findOrFail($id);
    
        return view('tags.show', compact('tag'));
    }
    

    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $tag->update($request->all());

        return redirect()->route('tags.index')->with('success', 'Tag mis à jour avec succès.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag supprimé avec succès.');
    }

    public function addFilmTag(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $film = Film::findOrFail($id);

            // Vérifier si le tag existe déjà
            $tag = Tag::firstOrCreate(['name' => $request->name]);

            // Lier le tag au film
            $film->tags()->syncWithoutDetaching($tag->id);

            return response()->json(['success' => true, 'tag' => $tag]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout du tag : ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Erreur lors de l\'ajout du tag'], 500);
        }
    }

    public function destroyFilmTag($filmId, $tagId)
    {
        try {
            $film = Film::findOrFail($filmId);
            $film->tags()->detach($tagId);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du tag : ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Erreur lors de la suppression du tag'], 500);
        }
    }


    public function addEpisodeTag(Request $request, $tvShowId, $seasonId, $episodeId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
    
            $episode = TvShowsEpisode::findOrFail($episodeId);
    
            // Vérifier si le tag existe déjà
            $tag = Tag::firstOrCreate(['name' => $request->name]);
    
            // Lier le tag à l'épisode
            $episode->tags()->syncWithoutDetaching($tag->id);
    
            return response()->json(['success' => true, 'tag' => $tag]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout du tag : ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Erreur lors de l\'ajout du tag'], 500);
        }
    }
    

    public function destroyTagFromEntity($entityType, $entityId, $tagId)
    {
        try {
            switch ($entityType) {
                case 'film':
                    $entity = Film::find($entityId);
                    break;
                case 'episode':
                    $entity = TvShowsEpisode::find($entityId);
                    break;
                // Ajoutez d'autres entités si nécessaire
                default:
                    return response()->json(['success' => false, 'error' => 'Type d\'entité inconnu'], 400);
            }

            if (!$entity) {
                return response()->json(['success' => false, 'error' => 'Entité non trouvée'], 404);
            }

            $entity->tags()->detach($tagId);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du tag : ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Erreur lors de la suppression du tag'], 500);
        }
    }


    public function addPubTag(Request $request, $pubId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $pub = Pub::findOrFail($pubId);

            // Vérifier si le tag existe déjà
            $tag = Tag::firstOrCreate(['name' => $request->name]);

            // Lier le tag à la pub
            $pub->tags()->syncWithoutDetaching($tag->id);

            return response()->json(['success' => true, 'tag' => $tag]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout du tag : ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Erreur lors de l\'ajout du tag'], 500);
        }
    }








}
