<?php

namespace App\Http\Controllers;

use App\Models\BlocPub;
use App\Models\Program;
use App\Models\Pub;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlocPubController extends Controller
{
    public function create()
    {
        $programs = Program::all();
        return view('blocpubs.create', compact('programs'));
    }

 
    public function store(Request $request)
    {
        $request->merge([
            'include_intro' => $request->has('include_intro'),
            'include_outro' => $request->has('include_outro'),
        ]);
    
        $validatedData = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'include_intro' => 'boolean',
            'include_outro' => 'boolean',
            'number_of_pubs' => 'required|integer|min:1',
            'ad_types' => 'required|array',
            'ad_types.*' => 'string',
            'start_year' => 'required|integer',
            'end_year' => 'required|integer',
        ]);
    
        // Sélectionner des intros et outros aléatoires
        $introOutro = $this->selectRandomIntroOutro($validatedData['start_year'], $validatedData['end_year']);
    
        $blocPubName = 'BlocPub_' . uniqid();
    
        $blocPub = BlocPub::create([
            'name' => $blocPubName,
            'program_id' => $validatedData['program_id'],
            'include_intro' => $validatedData['include_intro'],
            'include_outro' => $validatedData['include_outro'],
            'number_of_pubs' => $validatedData['number_of_pubs'],
            'ad_types' => json_encode($validatedData['ad_types']),
            'start_year' => $validatedData['start_year'],
            'end_year' => $validatedData['end_year'],
        ]);
    
        $pubs = Pub::whereIn('ad_type', $validatedData['ad_types'])
                   ->whereBetween('year', [$validatedData['start_year'], $validatedData['end_year']])
                   ->get()
                   ->shuffle();
    
        $brandsWithCommercial = [];
        $addedPubs = [];
        $order = 1;
        $totalDuration = 0;
    
        // Ajouter l'intro si sélectionné
        if ($blocPub->include_intro && $introOutro['intro']) {
            $blocPub->pubs()->attach($introOutro['intro']->id, ['order' => $order++]);
            $totalDuration += $this->convertDurationToSeconds($introOutro['intro']->duration);
        }
    
        // Ajouter les pubs jusqu'à atteindre le nombre souhaité
        foreach ($pubs as $pub) {
            if (count($addedPubs) >= $validatedData['number_of_pubs']) {
                break;
            }
    
            // Vérifier si la pub commerciale de ce brand a déjà été ajoutée
            if ($pub->ad_type === 'Commercial' && in_array($pub->brand_store_id, $brandsWithCommercial)) {
                continue;
            }
    
            // Vérifier si le dernier ad_type ajouté est le même que celui actuel
            if (!empty($addedPubs) && $addedPubs[count($addedPubs) - 1]['ad_type'] === $pub->ad_type) {
                continue;
            }
    
            $blocPub->pubs()->attach($pub->id, ['order' => $order++]);
            $totalDuration += $this->convertDurationToSeconds($pub->duration);
            $addedPubs[] = ['id' => $pub->id, 'ad_type' => $pub->ad_type];
            
            if ($pub->ad_type === 'Commercial') {
                $brandsWithCommercial[] = $pub->brand_store_id; // Marquer ce brand comme ayant une pub commerciale
            }
        }
    
        // Ajouter l'outro si sélectionné
        if ($blocPub->include_outro && $introOutro['outro']) {
            $blocPub->pubs()->attach($introOutro['outro']->id, ['order' => $order++]);
            $totalDuration += $this->convertDurationToSeconds($introOutro['outro']->duration);
        }
    
        $minutes = floor($totalDuration / 60);
        $seconds = $totalDuration % 60;
    
        $formattedDuration = sprintf('%d minutes %02d secondes', $minutes, $seconds);
    
        $blocPub->update([
            'duration' => $formattedDuration
        ]);
    
        return redirect()->route('blocPubs.show', $blocPub->id)->with('success', 'Bloc publicitaire créé avec succès.');
    }
    
    public function selectRandomIntroOutro($startYear, $endYear)
    {
        // Sélectionner un intro aléatoire
        $intro = Pub::where('ad_type', 'MTV Intro')
                    ->whereBetween('year', [$startYear, $endYear])
                    ->inRandomOrder()
                    ->first();
    
        // Sélectionner un outro aléatoire
        $outro = Pub::where('ad_type', 'MTV Outro')
                    ->whereBetween('year', [$startYear, $endYear])
                    ->inRandomOrder()
                    ->first();
    
        return ['intro' => $intro, 'outro' => $outro];
    }
    
    private function convertDurationToSeconds($duration)
    {
        list($hours, $minutes, $seconds) = explode(':', $duration);
        return $hours * 3600 + $minutes * 60 + $seconds;
    }
    

    



    public function index()
    {
        $blocPubs = BlocPub::all();
        return view('blocpubs.index', compact('blocPubs'));
    }

    public function show($id)
    {
        $blocPub = BlocPub::with('pubs')->findOrFail($id);
        return view('blocpubs.show', compact('blocPub'));
    }

    public function edit($id)
    {
        $blocPub = BlocPub::findOrFail($id);
        $programs = Program::all();
        return view('blocpubs.edit', compact('blocPub', 'programs'));
    }

    
    public function update(Request $request, BlocPub $blocPub)
    {
        $request->merge([
            'include_intro' => $request->has('include_intro'),
            'include_outro' => $request->has('include_outro'),
        ]);
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'include_intro' => 'boolean',
            'include_outro' => 'boolean',
            'pubs' => 'required|array',
            'pubs.*.id' => 'required|exists:pubs,id',
            'pubs.*.order' => 'required|integer',
        ]);
    
        // Mettre à jour BlocPub
        $blocPub->update([
            'name' => $validatedData['name'],
            'program_id' => $validatedData['program_id'],
            'include_intro' => $validatedData['include_intro'],
            'include_outro' => $validatedData['include_outro'],
        ]);
    
        // Détacher toutes les pubs pour éviter les conflits
        $blocPub->pubs()->detach();
    
        // Réattacher les pubs avec le nouvel ordre
        foreach ($validatedData['pubs'] as $pubData) {
            $blocPub->pubs()->attach($pubData['id'], ['order' => $pubData['order']]);
        }
    
        return redirect()->route('blocPubs.show', $blocPub->id)->with('success', 'Bloc publicitaire mis à jour avec succès.');
    }
    
    
    
    
    
    
  





    public function destroy($id)
    {
        $blocPub = BlocPub::findOrFail($id);
        $blocPub->delete();

        return redirect()->route('blocPubs.index')->with('success', 'Bloc publicitaire supprimé avec succès.');
    }
}

