<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrisonerRequest;
use App\Http\Requests\UpdatePrisonerRequest;
use App\Models\Ire;
use App\Models\Prison;
use App\Models\Prisoner;
use App\Models\PrisonerCharges;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Request;
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
//            'Non Security case' => 0,
        ];


        $total_prisoners_region_wise['Not Set'] = 0;
//        foreach (Prisoner::prisoner_status() as $key) {
//            $total_prisoners[$key] = 0;
//        }

        $total_prisoners['Undertrial'] = 0;
        $total_prisoners['Sentenced'] = 0;
        $total_prisoners['Death Sentenced'] = 0;

        foreach (Prisoner::regions() as $key => $value) {
            $total_prisoners_region_wise[$key] = 0;
        }

        foreach (Prisoner::crime_charges() as $key => $value) {
            $total_prisoners_crime_wise[$key] = 0;
        }

        $query_total_prisoners = DB::table('prisoners')->select('status', DB::raw("COUNT(DISTINCT prisoners.id) as total"))
            ->whereIn('prisoners.status', ['Undertrial', 'Sentenced', 'Death Sentenced'])
            ->whereNotNull('prisoners.prison')
            ->where('prisoners.case_closed', '=', 'No')
            ->groupBy('status')->get();

//        dd($query_total_prisoners);

        $query_total_prisoners_region_wise = DB::table('prisoners')->select('region', DB::raw("COUNT(*) as total"))->whereNotIn('prisoners.case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])->where('prisoners.status', '!=', 'Released')->groupBy('region')->get();

        $query_total_prisoners_crime_wise = DB::table('prisoners')->select('prisoner_charges.crime_charges', DB::raw("COUNT('prisoner_charges.crime_charges') as total"))->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')->where('prisoners.status', '!=', 'Released')->whereNotIn('prisoners.case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])->groupBy('prisoner_charges.crime_charges')->get();

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

        $prisoners_arrested_in_last_3_months = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->whereNotIn('prisoners.case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->whereBetween('gregorian_detention_date', [$dateS->format('Y-m-d'), $dateE->format('Y-m-d')])
            ->first()->total;

//        DB::enableQueryLog();
        $prisoners_released_in_last_3_months = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->where('status', 'Released')
            ->orWhere('prisoners.case_closing_reason', 'Deported')
            ->whereBetween('case_closing_date_gg', [$dateS->format('Y-m-d 00:00:00'), $dateE->format('Y-m-d 23:59:59')])
            ->first()->total;
//        dd(DB::getQueryLog($prisoners_released_in_last_3_months));
//        DB::enableQueryLog();
        $dateStart = Carbon::now();
        $dateEnd = Carbon::now()->addMonth(3);
        $prisoners_to_be_released_in_3_months = DB::table('prisoners')->select(DB::raw("count(*) as total"))
            ->whereNotIn('prisoners.case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->whereBetween('expected_release_date', [$dateStart->format('Y-m-d'), $dateEnd->format('Y-m-d')])
            ->first()->total;


        $financial_claim = DB::table('prisoners')->select(DB::raw("SUM(IF(financial_claim < 10001,financial_claim,0)) as 'under_10000',SUM(IF(financial_claim BETWEEN 10001 and 50000,financial_claim,0)) as 'from_10001_50000',SUM(IF(financial_claim BETWEEN 50001 and 75000,financial_claim,0)) as 'from_50001_75000',SUM(IF(financial_claim BETWEEN 75001 and 100000,financial_claim,0)) as 'from_75001_100000',SUM(IF(financial_claim BETWEEN 100001 and 1000000,financial_claim,0)) as 'from_100001_1000000'"))->where('prisoners.status', '!=', 'Released')->get();
        $financial_claim_10000 = DB::table('prisoners')
            ->select(DB::raw("SUM(financial_claim) as financial_claim"), DB::raw("COUNT(*) as total"))
            ->where('financial_claim', '<', 10001)
            ->where('status', '!=', 'Released')
            ->whereNotIn('case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->get();
        $financial_claim_50000 = DB::table('prisoners')
            ->select(DB::raw("SUM(financial_claim) as financial_claim"), DB::raw("COUNT(*) as total"))
            ->where('financial_claim', '>=', 10001)
            ->where('financial_claim', '<=', 50000)
            ->where('status', '!=', 'Released')
            ->whereNotIn('case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->get();
        $financial_claim_75000 = DB::table('prisoners')
            ->select(DB::raw("SUM(financial_claim) as financial_claim"), DB::raw("COUNT(*) as total"))
            ->where('financial_claim', '>=', 50001)
            ->where('financial_claim', '<=', 75000)
            ->where('status', '!=', 'Released')
            ->whereNotIn('case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->get();
        $financial_claim_100000 = DB::table('prisoners')
            ->select(DB::raw("SUM(financial_claim) as financial_claim"), DB::raw("COUNT(*) as total"))
            ->where('financial_claim', '>=', 75001)
            ->where('financial_claim', '<=', 100000)
            ->where('status', '!=', 'Released')
            ->whereNotIn('case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->get();
        $financial_claim_1000000 = DB::table('prisoners')
            ->select(DB::raw("SUM(financial_claim) as financial_claim"), DB::raw("COUNT(*) as total"))
            ->where('financial_claim', '>=', 100001)
            ->where('financial_claim', '<=', 1000000)
            ->where('status', '!=', 'Released')
            ->whereNotIn('case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->get();


        $financial_claim_data = [
            'Under 10000 (' . $financial_claim_10000->sum('total') . ')' => $financial_claim_10000->sum('financial_claim'),
            '10001 - 50000 (' . $financial_claim_50000->sum('total') . ')' => $financial_claim_50000->sum('financial_claim'),
            '50001 - 75000 (' . $financial_claim_75000->sum('total') . ')' => $financial_claim_75000->sum('financial_claim'),
            '75001 - 100000 (' . $financial_claim_100000->sum('total') . ')' => $financial_claim_100000->sum('financial_claim'),
            '100001 - 1000000+ (' . $financial_claim_1000000->sum('total') . ')' => $financial_claim_1000000->sum('financial_claim'),
        ];

        $private_right = $financial_claim_10000->sum('total') + $financial_claim_50000->sum('total') + $financial_claim_75000->sum('total') + $financial_claim_100000->sum('total') + $financial_claim_1000000->sum('total');

//        dd($financial_claim_10000->sum('financial_claim'));

        $dateStartOneYear = Carbon::now()->subMonth(3);
        $dateEndOneYear = Carbon::now();

        $dateStartTwoYear = Carbon::now()->subMonth(6);
        $dateEndTwoYear = Carbon::now();

        $dateStartNYear = Carbon::now()->subYear(2);
        $dateEndNYear = Carbon::now();


        $three_month_starting = Carbon::now()->subMonth(3);
        $three_month_ending = Carbon::now();

        $six_months_starting = Carbon::now()->subMonth(6);
        $six_months_ending = Carbon::now();

        $one_year_starting = Carbon::now()->subYear(2);
        $one_year_ending = Carbon::now();


        $total_delayed = $delay_after_completion_n_year = DB::table('prisoners')
            ->select(DB::raw("count(*) as total"))
            ->whereBetween('expected_release_date', [$one_year_starting->format('Y-m-d'), $one_year_ending->format('Y-m-d')])
            ->where('status', '!=', 'Released')
            ->where('case_closed', 'No')
            ->where('case_closing_reason', '!=', 'Deported')
            ->first()->total;


        $delay_after_completion_one_year = DB::table('prisoners')
            ->select(DB::raw("count(*) as total"))
            ->whereBetween('expected_release_date', [$three_month_starting->format('Y-m-d'), $three_month_ending->format('Y-m-d')])
            ->where('status', '!=', 'Released')
            ->where('case_closed', 'No')
            ->where('case_closing_reason', '!=', 'Deported')
            ->first()->total;


        $delay_after_completion_two_year = DB::table('prisoners')
            ->select(DB::raw("count(*) as total"))
            ->whereBetween('expected_release_date', [$six_months_starting->format('Y-m-d'), $six_months_ending->format('Y-m-d')])
            ->where('status', '!=', 'Released')
            ->where('case_closed', 'No')
            ->where('case_closing_reason', '!=', 'Deported')
            ->first()->total;


        $delay_after_completion_n_year = DB::table('prisoners')
            ->select(DB::raw("count(*) as total"))
            ->whereBetween('expected_release_date', [$one_year_starting->format('Y-m-d'), $one_year_ending->format('Y-m-d')])
            ->where('status', '!=', 'Released')
            ->where('case_closed', 'No')
            ->where('case_closing_reason', '!=', 'Deported')
            ->first()->total;


        $delay_after_completion = [
            '3M' => $delay_after_completion_one_year,
            '6M' => $delay_after_completion_two_year - $delay_after_completion_one_year,
            '1Y+' => $delay_after_completion_n_year - $delay_after_completion_two_year,
        ];


        $legal_assistance = DB::table('assistances')
            ->select(DB::raw("COUNT(prisoner_id) as total"))
            ->where('type', '=', 'Legal Assistance')
            ->orWhere('type', '=', 'Counselor Access')
            ->whereBetween('date', [$dateS->format('Y-m-d'), $dateE->format('Y-m-d')])
            ->groupBy('prisoner_id')
            ->first();


        $dateStartNYear_1 = Carbon::now()->subMonth(6);
        $dateEndNYear_1 = Carbon::now();


        $six_months = DB::table('prisoners')
            ->select('prisoners.name_and_father_name', 'prisoners.gregorian_detention_date', 'prisoner_charges.crime_charges')
            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
            ->where('prisoner_charges.crime_charges', '=', 'Security case')
            ->whereBetween('prisoners.gregorian_detention_date', [$dateStartNYear_1->format('Y-m-d'), $dateEndNYear_1->format('Y-m-d')])
            ->where('prisoners.status', '!=', 'Released')
            ->where('prisoners.case_closing_reason', '!=', 'Deported')
            ->get();


        $one_year_start = Carbon::parse($dateStartNYear_1)->subYear();
        $one_year_end = Carbon::parse($dateStartNYear_1);


        $one_year = DB::table('prisoners')
            ->select('prisoners.name_and_father_name', 'prisoners.gregorian_detention_date', 'prisoner_charges.crime_charges')
            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
            ->where('prisoner_charges.crime_charges', '=', 'Security case')
            ->whereBetween('prisoners.gregorian_detention_date', [$one_year_start->format('Y-m-d'), $one_year_end->format('Y-m-d')])
            ->where('prisoners.status', '!=', 'Released')
            ->where('prisoners.case_closing_reason', '!=', 'Deported')
            ->get();

        $two_half_year_end = Carbon::parse($one_year_start);
        $two_half_year_start = Carbon::parse($one_year_start)->subYear(2)->subMonth(6);
//        dd($two_half_year_start->format('d-m-Y'));


        $two_and_half_year = DB::table('prisoners')
            ->select('prisoners.name_and_father_name', 'prisoners.gregorian_detention_date', 'prisoner_charges.crime_charges')
            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
            ->where('prisoner_charges.crime_charges', '=', 'Security case')
            ->whereBetween('prisoners.gregorian_detention_date', [$two_half_year_start->format('Y-m-d'), $two_half_year_end->format('Y-m-d')])
            ->where('prisoners.status', '!=', 'Released')
            ->where('prisoners.case_closing_reason', '!=', 'Deported')
            ->get();


//
//        $three_year_plus = DB::table('prisoners')
//            ->select('prisoners.name_and_father_name', 'prisoners.gregorian_detention_date', 'prisoner_charges.crime_charges')
//            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
//            ->where('prisoner_charges.crime_charges', '=', 'Security case')
//            ->whereBetween('prisoners.gregorian_detention_date', [$dateStartNYear_1->subMonth(12)->format('Y-m-d'), $dateEndNYear_1->format('Y-m-d')])
//            ->where('prisoners.status', '!=', 'Released')
//            ->where('prisoners.case_closing_reason', '!=', 'Deported')
//            ->get();


        $total_sc_cases = DB::table('prisoners')
            ->select('prisoners.name_and_father_name', 'prisoners.gregorian_detention_date', 'prisoner_charges.crime_charges')
            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
            ->where('prisoner_charges.crime_charges', '=', 'Security case')
            ->where('prisoners.status', '!=', 'Released')
            ->where('prisoners.case_closing_reason', '!=', 'Deported')
            ->count();


        $security_cases = [
            '6 Months' => $six_months->count(),
            '1 Year' => $one_year->count(),
            '2.5 Years' => $two_and_half_year->count(),
        ];


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
            'legal_assistance',
            'private_right',
            'security_cases',
            'total_sc_cases',
            'total_delayed',
        ]));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prisoner = QueryBuilder::for(Prisoner::class)
            ->allowedIncludes(['prisoner_charges'])
            ->with('prisoner_charges')
            ->allowedFilters([
                AllowedFilter::scope('search_charges'),
                AllowedFilter::scope('search_string'),
                AllowedFilter::scope('search_date'),
                AllowedFilter::scope('search_from'),
                AllowedFilter::scope('expected_release_date'),
                AllowedFilter::scope('search_released'),
                AllowedFilter::scope('search_expected'),
                AllowedFilter::exact('cnic'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('passport_no'),
                AllowedFilter::exact('iqama_no'),
                AllowedFilter::exact('case_closing_reason'),
                AllowedFilter::exact('case_closed'),
                AllowedFilter::exact('detention_authority'),
                AllowedFilter::exact('region'),
                AllowedFilter::exact('detention_city'),
                AllowedFilter::exact('prison'),
            ])->latest()->paginate(50)->withQueryString();

        $prisoner_print = QueryBuilder::for(Prisoner::with('prisoner_charges', 'prisoner_shifting'))
            ->allowedIncludes(['prisoner_charges'])
            ->with('prisoner_charges')
            ->allowedFilters([
                AllowedFilter::scope('search_charges'),
                AllowedFilter::scope('search_string'),
                AllowedFilter::scope('search_date'),
                AllowedFilter::scope('search_from'),
                AllowedFilter::scope('expected_release_date'),
                AllowedFilter::scope('search_released'),
                AllowedFilter::scope('search_expected'),
                AllowedFilter::exact('cnic'),
                AllowedFilter::exact('detention_authority'),
                AllowedFilter::exact('region'),
                AllowedFilter::exact('detention_city'),
                AllowedFilter::exact('prison'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('passport_no'),
                AllowedFilter::exact('iqama_no'),
                AllowedFilter::exact('case_closing_reason'),
                AllowedFilter::exact('case_closed')
            ])->get();

        return view('prisoner.index', compact('prisoner', 'prisoner_print'));
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



        if ($request->input('prison')) {
            $prison = Prison::where('jail', $request->prison)->first();
            $data_set = NULL;
            if (!empty($prison)) {
                $data_set = $prison->region;
            }
            $request->merge(['region' => $data_set]);
            $request->merge(['detention_city' => $prison->detention_city]);
        }


        if ($request->input('detention_city')) {
            $detention_city = Prison::where('detention_city', $request->detention_city)->first();
            if (!empty($detention_city)) {
                $request->merge(['region' => $detention_city->region]);
                $request->merge(['jail' => $detention_city->jail]);
            }
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
            'detention_place' => $request->detention_place,
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
            'detention_place' => $request->detention_place,
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

    public function ajaxRequest(\Illuminate\Http\Request $request)
    {
        $found = null;
        if ($request->input('iqama_no')) {
            $found = Prisoner::where('iqama_no', $request->iqama_no)->first();
        }

        if (!empty($found)) {
            return response()->json(['data' => $found]);
        } else {
            return response()->json(['status' => 0]);
        }

    }
}
