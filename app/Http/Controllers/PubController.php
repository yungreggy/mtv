<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pub;
use App\Models\BrandStore;

class PubController extends Controller
{
    public function index(Request $request)
    {
        $query = Pub::query();

        // Gestion des filtres de tri
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'year':
                    $query->orderBy('year', 'asc');
                    break;
                case 'date_added':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        if ($request->has('type')) {
            $query->where('ad_type', $request->type);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('letter')) {
            $query->where('name', 'like', $request->letter . '%');
        }

        $pubs = $query->paginate(100);
        $adTypes = Pub::select('ad_type')->distinct()->get();

        return view('pubs.index', compact('pubs', 'adTypes'));
    }

    public function create()
    {
        // $brandsStores = BrandStore::all();
        return view('pubs.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_store' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'year' => 'required|integer',
            'file_path' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:20480',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'ad_type' => 'nullable|string|max:100',
            'target_demographic' => 'nullable|string|max:255',
            'frequency' => 'nullable|integer',
        ]);
    
        // Ajouter ":00" à la durée si elle n'a pas de secondes
        if (!empty($validated['duration']) && strlen($validated['duration']) == 5) {
            $validated['duration'] .= ':00';
        }
    
        // Check if a new brand store needs to be created
        if (!empty($validated['brand_store'])) {
            $brandStore = BrandStore::firstOrCreate(['name' => $validated['brand_store']]);
            $validated['brand_store_id'] = $brandStore->id;
        } else {
            $validated['brand_store_id'] = null;
        }
    
        $pub = new Pub();
        $pub->name = $validated['name'];
        $pub->brand_store_id = $validated['brand_store_id'];
        $pub->description = $validated['description'];
        $pub->duration = $validated['duration'] ?? null;
        $pub->year = $validated['year'];
        $pub->ad_type = $validated['ad_type'];
        $pub->target_demographic = $validated['target_demographic'];
        $pub->frequency = $validated['frequency'];
    
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('public/pubs');
            $pub->file_path = $filePath;
        }
    
        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')->store('public/thumbnails');
            $pub->thumbnail_image = $thumbnailPath;
        }
    
        $pub->save();
    
        return redirect()->route('pubs.index')->with('success', 'Publicité ajoutée avec succès.');
    }
    
    public function show($id)
    {
        $pub = Pub::findOrFail($id);
        return view('pubs.show', compact('pub'));
    }

    public function edit($id)
    {
        $pub = Pub::findOrFail($id);
        $brandsStores = BrandStore::all();
        return view('pubs.edit', compact('pub', 'brandsStores'));
    }

    public function update(Request $request, $id)
    {
        // Validation des données entrées par l'utilisateur
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_store' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'year' => 'required|integer',
            'file_path' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:20480',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'ad_type' => 'nullable|string|max:100',
            'target_demographic' => 'nullable|string|max:255',
            'frequency' => 'nullable|integer',
        ]);
    
        // Récupérer la publicité existante
        $pub = Pub::findOrFail($id);
    
        // Mise à jour des champs de la publicité
        $pub->name = $validated['name'];
        $pub->description = $validated['description'];
        $pub->year = $validated['year'];
        $pub->ad_type = $validated['ad_type'];
        $pub->target_demographic = $validated['target_demographic'];
        $pub->frequency = $validated['frequency'];
    
        // Gestion de la durée avec ajout de secondes si nécessaire
        if (!empty($validated['duration'])) {
            // Vérifie si la durée inclut déjà les secondes, sinon ajoute ":00"
            if (strlen($validated['duration']) == 5) {
                $validated['duration'] .= ':00';
            }
            $pub->duration = $validated['duration'];
        } else {
            $pub->duration = null;
        }
    
        // Gestion de la marque/magasin
        if (!empty($validated['brand_store'])) {
            $brandStore = BrandStore::firstOrCreate(['name' => $validated['brand_store']]);
            $pub->brand_store_id = $brandStore->id;
        } else {
            $pub->brand_store_id = null;
        }
    
        // Gestion du fichier téléchargé
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('public/pubs');
            $pub->file_path = $filePath;
        }
    
        // Gestion de l'image miniature téléchargée
        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')->store('public/thumbnails');
            $pub->thumbnail_image = $thumbnailPath;
        }
    
        // Sauvegarde des modifications de la publicité
        $pub->save();
    
        // Redirection vers la page de la publicité avec un message de succès
        return redirect()->route('pubs.show', $pub->id)->with('success', 'Publicité mise à jour avec succès.');
    }
    
    
    public function destroy($id)
    {
        $pub = Pub::findOrFail($id);
        $pub->delete();

        return redirect()->route('pubs.index')->with('success', 'Publicité supprimée avec succès.');
    }
}
