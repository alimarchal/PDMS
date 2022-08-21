<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrisonerRequest;
use App\Http\Requests\UpdatePrisonerRequest;
use App\Models\Prisoner;
use App\Models\PrisonerCharges;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Exports\PrisonersExport;
use Maatwebsite\Excel\Facades\Excel;

class PrisonerController extends Controller
{

    public function export()
    {
        return Excel::download(new PrisonersExport, 'PrisonersExport.xlsx');
    }

    public function dashboard()
    {


        $total_prisoners = [];
        $total_prisoners_region_wise = [];
        $total_prisoners_crime_wise = [];
        $total_prisoners_security_case = [
            'Security case' => 0,
            'Non Security case' => 0,
        ];


        $total_prisoners_region_wise['Not Set'] = 0;
        foreach (Prisoner::prisoner_status() as $key) {
            $total_prisoners[$key] = 0;
        }
        foreach (Prisoner::regions() as $key => $value) {
            $total_prisoners_region_wise[$key] = 0;
        }

        foreach (Prisoner::crime_charges() as $key => $value) {
            $total_prisoners_crime_wise[$key] = 0;
        }

        $query_total_prisoners = DB::table('prisoners')->select('status', DB::raw("count(*) as total"))->groupBy('status')->get();

        $query_total_prisoners_region_wise = DB::table('prisoners')->select('region', DB::raw("COUNT(*) as total"))->groupBy('region')->get();

        $query_total_prisoners_crime_wise = DB::table('prisoners')->select('prisoner_charges.crime_charges', DB::raw("COUNT('prisoner_charges.crime_charges') as total"))->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')->where('prisoners.status', '!=', 'Released')->groupBy('prisoner_charges.crime_charges')->get();

        foreach ($query_total_prisoners as $item) {
            $total_prisoners[$item->status] = $item->total;
        }

        foreach ($query_total_prisoners_region_wise as $item) {
            if ($item->region == null) {
                $total_prisoners_region_wise['Not Set'] = $item->total;
            } else {
                $total_prisoners_region_wise[$item->region] = $item->total;
            }
        }

        foreach ($query_total_prisoners_crime_wise as $item) {
            $total_prisoners_crime_wise[$item->crime_charges] = $item->total;
        }


        if (!empty($query_total_prisoners_crime_wise->where('crime_charges', 'Security case')->first())) {
            $total_prisoners_security_case = [
                'Security Case' => $query_total_prisoners_crime_wise->where('crime_charges', 'Security case')->first()->total,
            ];
        }


        $dateS = Carbon::now()->subMonth(3);
        $dateE = Carbon::now();

        DB::enableQueryLog();
        $prisoners_arrested_in_last_3_months = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->whereBetween('gregorian_detention_date', [$dateS->format('Y-m-d'), $dateE->format('Y-m-d')])
            ->first()->total;

        $prisoners_released_in_last_3_months = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->where('status', 'Released')
            ->whereBetween('expected_release_date', [$dateS->format('Y-m-d'), $dateE->format('Y-m-d')])
            ->first()->total;


        $dateStart = Carbon::now();
        $dateEnd = Carbon::now()->addMonth(3);
        $prisoners_to_be_released_in_3_months = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->whereBetween('expected_release_date', [$dateStart->format('Y-m-d'), $dateEnd->format('Y-m-d')])
            ->first()->total;


        $financial_claim = DB::table('prisoners')->select(DB::raw("SUM(IF(financial_claim < 10001,financial_claim,0)) as 'under_10000',SUM(IF(financial_claim BETWEEN 10001 and 50000,financial_claim,0)) as 'from_10001_50000',SUM(IF(financial_claim BETWEEN 50001 and 75000,financial_claim,0)) as 'from_50001_75000',SUM(IF(financial_claim BETWEEN 75001 and 100000,financial_claim,0)) as 'from_75001_100000',SUM(IF(financial_claim BETWEEN 100001 and 1000000,financial_claim,0)) as 'from_100001_1000000'"))->get();

        $financial_claim_data = [
            'Under 10000' => $financial_claim[0]->under_10000,
            '10001 - 50000' => $financial_claim[0]->from_10001_50000,
            '50001 - 75000' => $financial_claim[0]->from_50001_75000,
            '75001 - 100000' => $financial_claim[0]->from_75001_100000,
            '100001 - 1000000' => $financial_claim[0]->from_100001_1000000,
        ];


        $dateStartOneYear = Carbon::now()->subYear(1);
        $dateEndOneYear = Carbon::now();

        $dateStartTwoYear = Carbon::now()->subYear(2);
        $dateEndTwoYear = Carbon::now();

        $dateStartNYear = Carbon::now()->subYear(10);
        $dateEndNYear = Carbon::now();

        $delay_after_completion_one_year = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->where('status','!=','Released')
            ->whereBetween('expected_release_date', [$dateStartOneYear->format('Y-m-d'), $dateEndOneYear->format('Y-m-d')])
            ->first()->total;


        $delay_after_completion_two_year = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->where('status','!=','Released')
            ->whereBetween('expected_release_date', [$dateStartTwoYear->format('Y-m-d'), $dateEndTwoYear->format('Y-m-d')])
            ->first()->total;


        $delay_after_completion_n_year = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->where('status','!=','Released')
            ->whereBetween('expected_release_date', [$dateStartNYear->format('Y-m-d'), $dateEndNYear->format('Y-m-d')])
            ->first()->total;


        $delay_after_completion = [
            '1Y' => $delay_after_completion_one_year,
            '2Y' => $delay_after_completion_two_year,
            '3Y+' => $delay_after_completion_n_year,
        ];


        $legal_assistance = DB::table('assistances')
            ->select(DB::raw("COUNT(prisoner_id) as total"))
            ->where('type','=','Legal Assistance')
            ->whereBetween('created_at', [$dateS->format('Y-m-d'), $dateE->format('Y-m-d')])
            ->groupBy('prisoner_id')
            ->first();

        return view('dashboard', compact([
            'total_prisoners',
            'total_prisoners_region_wise',
            'total_prisoners_crime_wise',
            'total_prisoners_security_case',
            'prisoners_arrested_in_last_3_months',
            'prisoners_released_in_last_3_months',
            'prisoners_to_be_released_in_3_months',
            'financial_claim_data',
            'delay_after_completion',
            'legal_assistance'
        ]));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prisoner = QueryBuilder::for(Prisoner::with('prisoner_charges', 'prisoner_shifting'))
            ->allowedFilters([
                AllowedFilter::scope('search_string'),
                AllowedFilter::exact('cnic'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('region'),
                AllowedFilter::exact('prison'),
                AllowedFilter::exact('passport_no'),
                AllowedFilter::exact('iqama_no')
            ])->latest()->paginate(50)->withQueryString();




        return view('prisoner.index', compact('prisoner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('prisoner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePrisonerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePrisonerRequest $request)
    {


        if ($request->hasFile('photo_1')) {
            $path = $request->file('photo_1')->store('', 'public');
            $request->merge(['photo' => $path]);
        }

        if ($request->hasFile('passport_1')) {
            $path = $request->file('passport_1')->store('', 'public');
            $request->merge(['passport' => $path]);
        }


        if ($request->hasFile('iqama_1')) {
            $path = $request->file('iqama_1')->store('', 'public');
            $request->merge(['iqama' => $path]);
        }


        if ($request->hasFile('other_1')) {
            $path = $request->file('other_1')->store('', 'public');
            $request->merge(['other' => $path]);
        }


        if ($request->input('case_closing_date_hijri')) {
            if (strlen($request->case_closing_date_hijri) >= 10) {
                $url = "http://api.aladhan.com/v1/hToG?date=" . Carbon::parse($request->case_closing_date_hijri)->format('d-m-Y');
                $response = Http::get($url);
                $json_format = $response->json();
                $gg_date = $json_format['data']['gregorian']['date'];
                $date_in_gg = Carbon::createFromFormat('d-m-Y', $gg_date);
                $request->merge(['case_closing_date_gg' => $date_in_gg->format('Y-m-d')]);
            }
        }

        if ($request->input('hijri_detention_date')) {
            if (strlen($request->hijri_detention_date) >= 10) {
                $url = "http://api.aladhan.com/v1/hToG?date=" . Carbon::parse($request->hijri_detention_date)->format('d-m-Y');
                $response = Http::get($url);
                $json_format = $response->json();
                $gg_date = $json_format['data']['gregorian']['date'];
                $date_in_gg = Carbon::createFromFormat('d-m-Y', $gg_date);
                $request->merge(['gregorian_detention_date' => $date_in_gg->format('Y-m-d')]);
            }
        }

        $expected_release_date = null;
        if ($request->input('sentence_in_years') || $request->input('sentence_in_months')) {
            $expected_release_date = Carbon::parse($request->gregorian_detention_date);

            if ($request->sentence_in_years >= 0) {
                $expected_release_date = $expected_release_date->addYear($request->sentence_in_years);
            }

            if ($request->sentence_in_months >= 0) {
                $expected_release_date = $expected_release_date->addMonth($request->sentence_in_months);
            }

            $request->merge(['expected_release_date' => $expected_release_date->format('Y-m-d')]);
        }


        $prisoner = Prisoner::create([
            'name_and_father_name' => $request->name_and_father_name,
            'arabic_name' => $request->arabic_name,
            'iqama_no' => $request->iqama_no,
            'passport_no' => $request->passport_no,
            'detention_authority' => $request->detention_authority,
            'region' => $request->region,
            'detention_city' => $request->detention_city,
            'prison' => $request->prison,
            'gender' => $request->gender,
            'cnic' => $request->cnic,
            'hijri_detention_date' => $request->hijri_detention_date,
            'gregorian_detention_date' => $request->gregorian_detention_date,
            'case_details' => $request->case_details,
            'sentence_in_years' => $request->sentence_in_years,
            'sentence_in_months' => $request->sentence_in_months,
            'financial_claim' => $request->financial_claim,
            'penalty_fine' => $request->penalty_fine,
            'case_court_name' => $request->case_court_name,
            'case_city' => $request->case_city,
            'case_number' => $request->case_number,
            'case_prisoner_number' => $request->case_prisoner_number,
            'case_claim_number' => $request->case_claim_number,
            'case_sadad_number' => $request->case_sadad_number,
            'case_claimer_name' => $request->case_claimer_name,
            'case_claimer_contact_number' => $request->case_claimer_contact_number,
            'case_consular_access_date' => $request->case_consular_access_date,
            'etd_issuance_date' => $request->etd_issuance_date,
            'etd_number' => $request->etd_number,
            'case_closed' => $request->case_closed,
            'case_closing_reason' => $request->case_closing_reason,
            'case_closing_date_hijri' => $request->case_closing_date_hijri,
            'date_of_birth' => $request->date_of_birth,
            'provinces' => $request->provinces,
            'district' => $request->district,
            'tehseel' => $request->tehseel,
            'muhallah_town' => $request->muhallah_town,
            'contact_no_in_pakistan' => $request->contact_no_in_pakistan,
            'case_closing_date_gg' => $request->case_closing_date_gg,
            'expected_release_date' => $request->expected_release_date,
            'photo' => $request->photo,
            'passport' => $request->passport,
            'iqama' => $request->iqama,
            'other' => $request->other,
            'status' => $request->status,
        ]);

        $prisoner_id = $prisoner->id;

        foreach ($request->crime_charges as $key => $value) {
            PrisonerCharges::create([
                'prisoner_id' => $prisoner->id,
                'crime_charges' => $value
            ]);
        }
//        dd($request->all());
        session()->flash('message', 'Prisioner information successfully added.');
        return to_route('prisoner.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Prisoner $prisoner
     * @return \Illuminate\Http\Response
     */
    public function show(Prisoner $prisoner)
    {
        return view('prisoner.show', compact('prisoner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Prisoner $prisoner
     * @return \Illuminate\Http\Response
     */
    public function edit(Prisoner $prisoner)
    {
        return view('prisoner.edit', compact('prisoner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePrisonerRequest $request
     * @param \App\Models\Prisoner $prisoner
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrisonerRequest $request, Prisoner $prisoner)
    {

        if ($request->hasFile('photo_1')) {
            $path = $request->file('photo_1')->store('', 'public');
            $request->merge(['photo' => $path]);
        }

        if ($request->hasFile('passport_1')) {
            $path = $request->file('passport_1')->store('', 'public');
            $request->merge(['passport' => $path]);
        }


        if ($request->hasFile('iqama_1')) {
            $path = $request->file('iqama_1')->store('', 'public');
            $request->merge(['iqama' => $path]);
        }


        if ($request->hasFile('other_1')) {
            $path = $request->file('other_1')->store('', 'public');
            $request->merge(['other' => $path]);
        }

        if ($request->input('case_closing_date_hijri')) {
            if (strlen($request->case_closing_date_hijri) >= 10) {
                $url = "http://api.aladhan.com/v1/hToG?date" . Carbon::parse($request->case_closing_date_hijri)->format('d-m-Y');
                $response = Http::get($url);
                $json_format = $response->json();
                $gg_date = $json_format['data']['gregorian']['date'];
                $date_in_gg = Carbon::createFromFormat('d-m-Y', $gg_date);
                $request->merge(['case_closing_date_gg' => $date_in_gg->format('Y-m-d')]);
            }
        }

        if ($request->input('hijri_detention_date')) {
            if (strlen($request->hijri_detention_date) >= 10) {
                $url = "http://api.aladhan.com/v1/hToG?date=" . Carbon::parse($request->hijri_detention_date)->format('d-m-Y');
                $response = Http::get($url);
                $json_format = $response->json();
                $gg_date = $json_format['data']['gregorian']['date'];
                $date_in_gg = Carbon::createFromFormat('d-m-Y', $gg_date);
                $request->merge(['gregorian_detention_date' => $date_in_gg->format('Y-m-d')]);
            }
        }

        $expected_release_date = null;
        if ($request->input('sentence_in_years') || $request->input('sentence_in_months')) {
            $expected_release_date = Carbon::parse($request->gregorian_detention_date);

            if ($request->sentence_in_years >= 0) {
                $expected_release_date = $expected_release_date->addYear($request->sentence_in_years);

            }

            if ($request->sentence_in_months >= 0) {
                $expected_release_date = $expected_release_date->addMonth($request->sentence_in_months);
            }

            $request->merge(['expected_release_date' => $expected_release_date->format('Y-m-d')]);
        }

        $prisoner->update([
            'name_and_father_name' => $request->name_and_father_name,
            'arabic_name' => $request->arabic_name,
            'iqama_no' => $request->iqama_no,
            'passport_no' => $request->passport_no,
            'detention_authority' => $request->detention_authority,
            'region' => $request->region,
            'detention_city' => $request->detention_city,
            'prison' => $request->prison,
            'gender' => $request->gender,
            'cnic' => $request->cnic,
            'hijri_detention_date' => $request->hijri_detention_date,
            'gregorian_detention_date' => $request->gregorian_detention_date,
            'case_details' => $request->case_details,
            'sentence_in_years' => $request->sentence_in_years,
            'sentence_in_months' => $request->sentence_in_months,
            'financial_claim' => $request->financial_claim,
            'penalty_fine' => $request->penalty_fine,
            'case_court_name' => $request->case_court_name,
            'case_city' => $request->case_city,
            'case_number' => $request->case_number,
            'case_prisoner_number' => $request->case_prisoner_number,
            'case_claim_number' => $request->case_claim_number,
            'case_sadad_number' => $request->case_sadad_number,
            'case_claimer_name' => $request->case_claimer_name,
            'case_claimer_contact_number' => $request->case_claimer_contact_number,
            'case_consular_access_date' => $request->case_consular_access_date,
            'etd_issuance_date' => $request->etd_issuance_date,
            'etd_number' => $request->etd_number,
            'case_closed' => $request->case_closed,
            'case_closing_reason' => $request->case_closing_reason,
            'case_closing_date_hijri' => $request->case_closing_date_hijri,
            'date_of_birth' => $request->date_of_birth,
            'provinces' => $request->provinces,
            'district' => $request->district,
            'tehseel' => $request->tehseel,
            'muhallah_town' => $request->muhallah_town,
            'contact_no_in_pakistan' => $request->contact_no_in_pakistan,
            'case_closing_date_gg' => $request->case_closing_date_gg,
            'expected_release_date' => $request->expected_release_date,
            'photo' => $request->photo,
            'passport' => $request->passport,
            'iqama' => $request->iqama,
            'other' => $request->other,
            'status' => $request->status,
        ]);


        $prisoner_id = $prisoner->id;

        $PrisonerCharges = PrisonerCharges::where('prisoner_id', $prisoner->id)->get();

        foreach ($PrisonerCharges as $pc) {
            $pc->delete();
        }

        foreach ($request->crime_charges as $key => $value) {
            PrisonerCharges::create([
                'prisoner_id' => $prisoner->id,
                'crime_charges' => $value
            ]);
        }

        session()->flash('message', 'Prisoner information successfully updated.');
        return to_route('prisoner.edit', $prisoner_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Prisoner $prisoner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prisoner $prisoner)
    {

        $prisoner_charges = PrisonerCharges::where('prisoner_id', $prisoner->id)->get();
        foreach ($prisoner_charges as $pc) {
            $pc->delete();
        }
        $prisoner->delete();
        session()->flash('message', 'Prisoner information successfully destroy.');
        return to_route('prisoner.index');
    }
}
