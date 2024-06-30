<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Channel;
use App\Models\ProgramDate;
use App\Models\ProgramSchedule;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        return view('programs.create');
    }


    public function store(Request $request)
    {
        // Validation des données de la requête
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
      
        
            'target_audience' => 'nullable|string',
         
            'premiere_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'channels' => 'required|array',
            'channels.*' => 'exists:channels,id',
        ]);
    
        // Création du programme
        $program = Program::create($data);
    
        // Association des chaînes au programme
        $program->channels()->sync($data['channels']);
    
        // Génération des dates entre start_date et end_date
        $startDate = new \DateTime($data['start_date']);
        $endDate = new \DateTime($data['end_date']);
        $interval = new \DateInterval('P1D'); // Intervalle de 1 jour
        $datePeriod = new \DatePeriod($startDate, $interval, $endDate->add($interval));
    
        // Sauvegarde des dates dans la table program_dates
        foreach ($datePeriod as $date) {
            DB::table('program_dates')->insert([
                'program_id' => $program->id,
                'date' => $date->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        // Redirection avec un message de succès
        return redirect()->route('programs.index')->with('success', 'Programme créé avec succès.');
    }
    
    public function show($id)
    {
        // Chargement du programme avec ses dates, horaires, épisodes liés et canaux
        $program = Program::with([
            'dates.schedules' => function($query) {
                $query->with(['episodes', 'films']); // Assurez-vous que les films et épisodes sont chargés
            },
            'channels'
        ])->findOrFail($id);
    
        // Utilisez dd() pour déboguer et afficher la structure des données chargées
        // dd($program->toArray());
    
        // Génération des jours du calendrier entre start_date et end_date
        $startDate = new \DateTime($program->start_date);
        $endDate = new \DateTime($program->end_date);
        $interval = new \DateInterval('P1D');
        $datePeriod = new \DatePeriod($startDate, $interval, $endDate->add($interval));
        $calendarDays = iterator_to_array($datePeriod);
    
        // Organisation des dates par mois
        $calendar = [];
        foreach ($calendarDays as $date) {
            $monthYear = $date->format('F Y');
            $calendar[$monthYear][] = $date;
        }
    
        // Jours de la semaine pour l'affichage
        $daysOfWeek = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    
        return view('programs.show', compact('program', 'calendar', 'daysOfWeek'));
    }
    
    
    
  
    

    public function edit(Program $program)
    {
        $channels = Channel::all();
        return view('programs.edit', compact('program', 'channels'));
    }
    
    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'target_audience' => 'required|string',
            'channels' => 'required|array',
            'channels.*' => 'exists:channels,id',
        ]);
    
        $program->update($data);
        $program->channels()->sync($data['channels']);
    
        // Mise à jour des dates entre start_date et end_date
        $program->dates()->delete(); // Supprime les anciennes dates
    
        $startDate = new \DateTime($data['start_date']);
        $endDate = new \DateTime($data['end_date']);
        $interval = new \DateInterval('P1D');
        $datePeriod = new \DatePeriod($startDate, $interval, $endDate->add($interval));
    
        foreach ($datePeriod as $date) {
            ProgramDate::create([
                'program_id' => $program->id,
                'date' => $date->format('Y-m-d'),
            ]);
        }
    
        return redirect()->route('programs.index')->with('success', 'Programme mis à jour avec succès.');
    }
    

    

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('programs.index');
    }
}

