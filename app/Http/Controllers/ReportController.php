<?php

namespace App\Http\Controllers;

use App\Models\Prison;
use App\Models\Prisoner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function reportMain()
    {
        return view('report.reportMain');
    }

    public function index()
    {
        $region_wise = [];
        $grand_total = [];
        foreach (Prison::orderBy('region')->get() as $item) {
            foreach (Prisoner::crime_charges() as $key => $value) {
                $region_wise[$item->region][$item->jail][$key] = 0;
                $grand_total[$key] = 0;
            }
        }


//        DB::enableQueryLog();
        $query_region_wise = DB::table('prisoners')
            ->select('prisons.region', 'prisons.jail', 'prisoner_charges.crime_charges', DB::raw("count(prisoner_charges.crime_charges) as total"))
            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
            ->join('prisons', 'prisoners.prison', '=', 'prisons.jail')
            ->whereNotIn('prisoners.case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->groupBy('prisons.region', 'prisons.jail', 'prisoner_charges.crime_charges')
            ->orderBy('prisoners.id', 'asc')
            ->get();

        foreach ($query_region_wise as $item) {
            $region_wise[$item->region][$item->jail][$item->crime_charges] = $item->total;
            $grand_total[$item->crime_charges] = $query_region_wise->where('crime_charges', $item->crime_charges)->sum('total');
        }

        return view('report.index', compact('region_wise', 'grand_total'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crimeWise()
    {
        $crime_wise = [];
        foreach (Prisoner::crime_charges() as $key => $value) {
            $crime_wise[$key] = 0;
        }

        $query = DB::table('prisoners')->select('prisoner_charges.crime_charges', DB::raw("COUNT(prisoners.id) as no_of_prisoners"))
            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
            ->whereNotIn('prisoners.case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->groupBy('prisoner_charges.crime_charges')
            ->get();
        foreach ($query as $item) {
            $crime_wise[$item->crime_charges] = $item->no_of_prisoners;
        }

        $total = $query->sum('no_of_prisoners');

        return view('report.crimeWise', compact('crime_wise', 'total'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function prisonWise()
    {
        $prison_wise = [];
        foreach (Prisoner::prisons() as $key => $value) {
            $prison_wise[$key] = 0;
        }

        $prison_wise['NotSet'] = 0;

        $query = DB::table('prisoners')
            ->select('prison', DB::raw("count(id) as prisoners"))
            ->whereNotIn('prisoners.case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->groupBy('prison')
            ->get();

        foreach ($query as $item) {
            if ($item->prison == null) {
                $prison_wise['NotSet'] = $item->prisoners;
            } else {
                $prison_wise[$item->prison] = $item->prisoners;
            }
        }

        $total = $query->sum('prisoners');

        return view('report.prisonWise', compact('prison_wise', 'total'));
    }

    public function regionWise()
    {
        $region_wise = [];
        foreach (Prisoner::regions() as $key => $value) {
            $region_wise[$key]['Detainee'] = 0;
            $region_wise[$key]['Undertrial'] = 0;
            $region_wise[$key]['Sentenced'] = 0;
            $region_wise[$key]['Death Sentenced'] = 0;
            $region_wise[$key]['Released'] = 0;
            $region_wise[$key]['Shifted'] = 0;
            $region_wise[$key]['Executed'] = 0;

        }
        $query = DB::table('prisoners')
            ->select('region', 'status', DB::raw("count(*) as prisoners"))
            ->whereNotIn('prisoners.case_closing_reason', ['Deported', 'Released', 'Executed', 'Unknown'])
            ->groupBy('region', 'status')
            ->get();
        foreach ($query as $item) {
            if ($item->region == null) {

            } else {
                $region_wise[$item->region][$item->status] = $item->prisoners;
            }
        }
        $total = $query->sum('prisoners');
//        foreach($region_wise as $key => $value)
//        {
//            dd($value);
//        }
//        dd($region_wise);
        return view('report.regionWise', compact('region_wise', 'total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
