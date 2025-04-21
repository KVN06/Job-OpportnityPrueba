<?php

namespace App\Http\Controllers;
use App\Models\Unemployed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnemployedController extends Controller
{
    public function create() {
        return view('Unemployed-form');
    }

    public function agg_unemployed(Request $request) {
        $unemployed = new Unemployed();
        $unemployed->user_id = Auth::id(); 
        $unemployed->profession = $request->profession;
        $unemployed->experience = $request->experience;
        $unemployed->location = $request->location;
        $unemployed->save();
    
        return redirect()->route('home'); // Redirigir después de guardar
    }
    
}
