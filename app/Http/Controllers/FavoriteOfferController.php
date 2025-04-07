<?php

namespace App\Http\Controllers;
use App\Models\FavoriteOffer;
use Illuminate\Http\Request;

class FavoriteOfferController extends Controller
{
    public function create() {
        return view('FavoriteOffer-form');
    }

    public function agg_favorite_offer(Request $request) {
        $favorite = new FavoriteOffer();
        $favorite->unemployed_id = $request->unemployed_id;
        $favorite->job_offer_id = $request->job_offer_id;
        $favorite->save();

        return $favorite;
    }
}
