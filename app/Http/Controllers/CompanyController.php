<?php

namespace App\Http\Controllers;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function create() {
        return view('Company-form');
    }

    public function agg_company(Request $request) {
        $company = new Company();
        $company->user_id = $request->user_id;
        $company->company_name = $request->company_name;
        $company->description = $request->description;
        $company->save();

        return $company;
    }
}
