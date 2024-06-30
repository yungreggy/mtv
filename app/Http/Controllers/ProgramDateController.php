<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Channel;
use App\Models\ProgramDate;
use App\Models\ProgramSchedule;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
    
    class ProgramDateController extends Controller
    {
        public function index()
        {
            $programDates = ProgramDate::all();
            return view('programDates.index', compact('programDates'));
        }
    
        public function create()
        {
            $programs = Program::all();
            return view('programDates.create', compact('programs'));
        }
    
        public function store(Request $request)
{
    $request->validate([
        'program_id' => 'required|exists:programs,id',
        'date' => 'required|date',
    ]);

    ProgramDate::create([
        'program_id' => $request->program_id,
        'date' => $request->date,
    ]);

    return redirect()->route('programDates.index')->with('success', 'Date de programme ajoutée avec succès.');
}

    
public function show($programId, $date)
{
    $program = Program::findOrFail($programId);
    $programDate = ProgramDate::where('program_id', $programId)
                               ->where('date', $date)
                               ->firstOrFail();

    $programSchedules = $programDate->schedules()
                                    ->with([
                                        'films' => function($query) use ($programDate) {
                                            $query->wherePivot('program_date_id', $programDate->id);
                                        },
                                        'films.genres',
                                        'episodes' => function($query) use ($programDate) {
                                            $query->where('program_schedule_episodes.program_date_id', $programDate->id);
                                        }
                                    ])
                                    ->orderBy('start_time')
                                    ->get();

    // Calcul des dates pour le jour précédent et le jour suivant
    $previousDate = \Carbon\Carbon::parse($date)->subDay()->toDateString();
    $nextDate = \Carbon\Carbon::parse($date)->addDay()->toDateString();

    return view('programDates.show', compact('program', 'programDate', 'programSchedules', 'previousDate', 'nextDate'));
}






        
        public function edit($id)
        {
            $programDate = ProgramDate::findOrFail($id);
            $programs = Program::all();
            return view('programDates.edit', compact('programDate', 'programs'));
        }
    
        public function update(Request $request, $id)
{
    $request->validate([
        'program_id' => 'required|exists:programs,id',
        'date' => 'required|date',
    ]);

    $programDate = ProgramDate::findOrFail($id);
    $programDate->update([
        'program_id' => $request->program_id,
        'date' => $request->date,
    ]);

    return redirect()->route('programDates.index')->with('success', 'Date de programme mise à jour avec succès.');
}

        public function destroy($id)
        {
            $programDate = ProgramDate::findOrFail($id);
            $programDate->delete();
    
            return redirect()->route('programDates.index')->with('success', 'Date de programme supprimée avec succès.');
        }
    
        public function attachSchedule(Request $request, $programDateId)
        {
            $request->validate([
                'schedule_id' => 'required|exists:program_schedules,id',
            ]);
    
            $programDate = ProgramDate::findOrFail($programDateId);
            $programDate->schedules()->attach($request->schedule_id);
    
            return redirect()->route('programDates.show', $programDateId)->with('success', 'Programme associé avec succès.');
        }
    
        public function detachSchedule($programDateId, $scheduleId)
        {
            $programDate = ProgramDate::findOrFail($programDateId);
            $programDate->schedules()->detach($scheduleId);
    
            return redirect()->route('programDates.show', $programDateId)->with('success', 'Programme dissocié avec succès.');
        }
    }
    





