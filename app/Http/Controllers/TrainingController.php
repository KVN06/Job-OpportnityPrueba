<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function create()
    {
        $trainings = Training::all(); 
        return view('training-form', compact('trainings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Training::create($request->all());

        return redirect()->route('training.create')->with('success', 'Capacitación guardada exitosamente.');
    }

    public function destroy($id)
    {
        $training = Training::findOrFail($id);
        $training->delete();

        return redirect()->route('training.create')->with('success', 'Capacitación eliminada.');
    }
}
