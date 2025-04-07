<?php

namespace App\Http\Controllers;
use App\Models\JobOffer;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    public function create() {
        return view('JobOffer-form');
    }

    public function agg_job_offer(Request $request) {
        $offer = new JobOffer();
        $offer->company_id = $request->company_id;
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->salary = $request->salary;
        $offer->location = $request->location;
        $offer->geolocation = $request->geolocation;
        $offer->offer_type = $request->offer_type;
        $offer->save();

        return $offer;
    }
}
