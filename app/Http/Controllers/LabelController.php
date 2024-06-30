<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Label;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::orderBy('name', 'asc')->paginate(30); // Récupère tous les labels en ordre alphabétique
        return view('musicVideos.labels.index', compact('labels'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('musicVideos.labels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:labels',
            'logo_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'foundation_year' => 'nullable|integer|min:1800|max:' . date('Y'),
        ]);

        $label = new Label();
        $label->name = $validated['name'];
        $label->website = $validated['website'];
        $label->description = $validated['description'];
        $label->foundation_year = $validated['foundation_year'];

        if ($request->hasFile('logo_image')) {
            $path = $request->file('logo_image')->store('public/labels');
            $label->logo_image = $path;
        }

        $label->save();

        return redirect()->route('labels.index')->with('success', 'Label ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $label = Label::with('albums.artist')->findOrFail($id);
        return view('musicVideos.labels.show', compact('label'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $label = Label::find($id);
        return view('musicVideos.labels.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation des données entrantes
        $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'foundation_year' => 'nullable|integer|min:1800|max:'.date('Y'),
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB Max
        ]);
    
        // Trouver le label par son ID
        $label = Label::findOrFail($id);
    
        // Mise à jour des attributs du label
        $label->name = $request->name;
        $label->website = $request->website;
        $label->description = $request->description;
        $label->foundation_year = $request->foundation_year;
    
        // Gestion de l'upload de l'image du logo
        if ($request->hasFile('logo_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($label->logo_image && file_exists(storage_path('app/public/' . $label->logo_image))) {
                \Storage::delete('public/' . $label->logo_image);
            }
    
            // Sauvegarder la nouvelle image
            $path = $request->file('logo_image')->store('logos', 'public');
            $label->logo_image = $path;
        }
    
        // Sauvegarder les changements
        $label->save();
    
        // Redirection vers une route appropriée avec un message de succès
        return redirect()->route('labels.index')->with('success', 'Label mis à jour avec succès.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Supprimer et détacher
        $label = Label::findOrFail($id);
        $label->delete();
        return redirect()->route('labels.index')->with('success', 'Label supprimé avec succès.');
       
    }



}
