<?php

namespace App\Http\Controllers;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function create() {
        return view('Training-form');
    }

    public function agg_training(Request $request) {
        $training = new Training();
        $training->title = $request->title;
        $training->description = $request->description;
        $training->link = $request->link;
        $training->provider = $request->provider;
        $training->start_date = $request->start_date;
        $training->end_date = $request->end_date;
        $training->save();

        return $training;
    }
}
