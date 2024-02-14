<?php

namespace App\Http\Controllers\Contributions;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Redirect;

class ContributionsController extends Controller
{

  public function sss(Request $r)
  {
    $company_id = $r->input('company');
    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');

    // invalid date inputed
    if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
    
    $companies = DB::table('company')->get();

    $sssContributions = DB::SELECT(DB::RAW('CALL SSS_statutory_contrib("'.$company_id.'","'.$date_from.'","'.$date_to.'")'));

    return view('Contributions.sss', [
        'company_id' => $company_id,
        'date_from' => $date_from,
        'date_to' => $date_to,
        'companies' => $companies,
        'sssContributions' => $sssContributions
    ]);

  }

  public function philhealth(Request $r)
  {
    $company_id = $r->input('company');
    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');

    // invalid date inputed
    if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
    
    $companies = DB::table('company')->get();

    $philhealthContributions = DB::SELECT(DB::RAW('CALL PHILHEALTH_statutory_contrib("'.$company_id.'","'.$date_from.'","'.$date_to.'")'));
    
    return view('Contributions.philhealth', [
        'company_id' => $company_id,
        'date_from' => $date_from,
        'date_to' => $date_to,
        'companies' => $companies,
        'philhealthContributions' => $philhealthContributions
    ]);
  }

  public function pagibig(Request $r)
  {
    $company_id = $r->input('company');
    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');

    // invalid date inputed
    if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
    
    $companies = DB::table('company')->get();

    $pagibigContributions = DB::SELECT(DB::RAW('CALL PAGIBIG_statutory_contrib("'.$company_id.'","'.$date_from.'","'.$date_to.'")'));
    
    return view('Contributions.pagibig', [
        'company_id' => $company_id,
        'date_from' => $date_from,
        'date_to' => $date_to,
        'companies' => $companies,
        'pagibigContributions' => $pagibigContributions
    ]);
  }

  public function _1601c(Request $r)
  {
    $company_id = $r->input('company');
    $date_from = $r->input('date_from') ?? date('Y-m-01');
    $date_to = $r->input('date_to') ?? date('Y-m-t');

    // invalid date inputed
    if (strtotime($date_from) > strtotime($date_to)) return Redirect::back()->withErrors(['Invalid date range.'])->withInput();
    
    $companies = DB::table('company')->get();

    $_1601c = DB::SELECT(DB::RAW('CALL get_1601C_per_company("'.$company_id.'","'.$date_from.'","'.$date_to.'")'));
    // dd($_1601c);

    $totals1601c = DB::SELECT(DB::RAW('CALL sum_1601C_per_company("'.$company_id.'","'.$date_from.'","'.$date_to.'")'));

    return view('Contributions.1601c', [
        'company_id' => $company_id,
        'date_from' => $date_from,
        'date_to' => $date_to,
        'companies' => $companies,
        '_1601c' => $_1601c,
        'totals1601c' => $totals1601c,
    ]);
  }

}