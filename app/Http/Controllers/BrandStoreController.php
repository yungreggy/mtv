<?php

namespace App\Http\Controllers;

use App\Models\BrandStore; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class BrandStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brandsStores = BrandStore::paginate(30); // Paginer à 30 éléments par page
        return view('pubs.brands.index', compact('brandsStores'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pubs.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:brands_stores|max:255',
            'description' => 'nullable',
            'logo_image' => 'nullable|image|max:2048' // Assure-toi que la validation de l'image est correcte selon tes besoins.
        ]);
    
        // Traitement de l'image
        if ($request->hasFile('logo_image')) {
            $filename = $request->logo_image->store('logos', 'public');
            $validatedData['logo_image'] = $filename;
        }
    
        $brandStore = new BrandStore($validatedData);
        $brandStore->save();
        
        return redirect()->route('brandsStores.index')->with('success', 'Marque/Magasin ajouté avec succès!');
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $brandStore = BrandStore::with(['pubs.blocPubs'])->findOrFail($id);
        return view('pubs.brands.show', compact('brandStore'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $brandStore = BrandStore::findOrFail($id);
        return view('pubs.brands.edit', compact('brandStore'));
    }

    /**
     * Update the specified resource in storage.
     */
  /**
 * Update the specified resource in storage.
 */
public function update(Request $request, $id)
{
    $brandStore = BrandStore::findOrFail($id);

    $validatedData = $request->validate([
        'name' => 'required|unique:brands_stores,name,' . $brandStore->id . '|max:255',
        'description' => 'nullable',
        'logo_image' => 'nullable|image|max:2048' // Assure-toi que la validation de l'image est correcte selon tes besoins.
    ]);

    // Traitement de l'image
    if ($request->hasFile('logo_image')) {
        // Supprime l'ancienne image si nécessaire
        if ($brandStore->logo_image) {
            Storage::delete('public/' . $brandStore->logo_image);
        }
        
        $filename = $request->logo_image->store('logos', 'public');
        $validatedData['logo_image'] = $filename;
    }

    $brandStore->update($validatedData);
    
    return redirect()->route('brandsStores.index')->with('success', 'Marque/Magasin mis à jour avec succès!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brandStore = BrandStore::findOrFail($id);
        $brandStore->delete();
        Session::flash('message', 'Brand/Store successfully deleted!');
        return Redirect::to('brandsStores');
    }
}
