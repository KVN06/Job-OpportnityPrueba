<?php

namespace App\Http\Controllers;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function create() {
        return view('Portfolio-form');
    }

    public function agg_portfolio(Request $request) {
        $portfolio = new Portfolio();
        $portfolio->unemployed_id = $request->unemployed_id;
        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->file_url = $request->file_url;
        $portfolio->save();

        return $portfolio;
    }
}
