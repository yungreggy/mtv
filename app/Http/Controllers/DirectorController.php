<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Director;


class DirectorController extends Controller
{



    public function index(Request $request)
{
    $query = Director::query();

    // Recherche par nom
    if ($request->has('search')) {
        $query->where('name', 'LIKE', '%' . $request->input('search') . '%');
    }

    // Tri par différents critères
    if ($request->has('sort_by')) {
        $sortBy = $request->input('sort_by');
        switch ($sortBy) {
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
        }
    } else {
        // Tri par défaut
        $query->orderBy('name', 'asc');
    }

    $directors = $query->paginate(40);

    return view('directors.index', compact('directors'));
}

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $directors = Director::where('name', 'LIKE', "%{$query}%")->get();

        return response()->json($directors);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('directors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
  
     public function store(Request $request)
     {
         // Valider les données d'entrée
         $validatedData = $request->validate([
             'name' => 'required|string|max:255',
         ]);
 
         // Créer un nouveau réalisateur
         $director = Director::create([
             'name' => $validatedData['name'],
         ]);
 
         // Rediriger avec un message de succès
         return redirect()->route('directors.index')
             ->with('success', 'Le réalisateur a été créé avec succès.');
     }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Précharge les films et les clips musicaux associés au réalisateur
        $director = Director::with(['films', 'musicVideos'])->findOrFail($id);
    
        // Envoie l'objet director à la vue
        return view('directors.show', compact('director'));
    }
    
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $director = Director::findOrFail($id);
        return view('directors.edit', compact('director'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $director = Director::findOrFail($id);
        $director->name = $validated['name'];
        $director->save();

        return redirect()->route('directors.show', $director->id)->with('success', 'Réalisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $director = Director::findOrFail($id);
        $director->delete();
    
        return redirect()->route('directors.index')->with('success', 'Réalisateur supprimé avec succès.');
    }
    
}
