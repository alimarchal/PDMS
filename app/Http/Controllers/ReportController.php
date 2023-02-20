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

        foreach (Prison::orderBy('sequence')->orderBy('jail', 'ASC')->where('status', 1)->get() as $item) {
            foreach (Prisoner::crime_charges() as $key => $value) {
                if (!empty($item->jail)) {
                    $region_wise[$item->region][$item->jail][$key] = 0;
                    $region_wise['Riyadh']['NotSet'][$key] = 0;
                    $region_wise['NotSet']['NotSet'][$key] = 0;
                    $grand_total[$key] = 0;
                }

            }
        }


//        dd($region_wise);

//        $query_region_wise = DB::table('prisoners')
//            ->select('prisons.region', 'prisons.jail', 'prisoner_charges.crime_charges', DB::raw('COUNT(DISTINCT prisoner_charges.prisoner_id) AS total'))
//            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
//            ->join('prisons', 'prisoners.prison', '=', 'prisons.jail')
//            ->whereIn('prisoners.status', ['Undertrial', 'Sentenced'])
//            ->where('prisoners.case_closed', '=', 'No')
//            ->groupBy('prisoners.region', 'prisoners.prison')
//            ->get();

        $query_region_wise = DB::table('prisoners')
            ->select('region', 'prison', 'prisoner_charges.crime_charges', DB::raw('COUNT(DISTINCT prisoner_charges.prisoner_id) as total'))
            ->join('prisoner_charges', 'prisoners.id', '=', 'prisoner_charges.prisoner_id')
            ->whereIn('prisoners.status', ['Undertrial', 'Sentenced'])
            ->where('prisoners.case_closed', '=', 'No')
            ->groupBy('region', 'prison')
            ->get();



//        dd($query_region_wise->sum('total'));


        foreach ($query_region_wise as $item) {
            if ($item->region == null) {
                if ($item->region == null && $item->prison == null) {
                    $region_wise['NotSet']['NotSet'][$item->crime_charges] = $item->total;
                }
            } else {
                if ($item->prison == null) {
                    $region_wise['Riyadh']['NotSet'][$item->crime_charges] = $item->total;
                } else {

                    $region_wise[$item->region][$item->prison][$item->crime_charges] = $item->total;
                }
            }
            $grand_total[$item->crime_charges] = $query_region_wise->where('crime_charges', $item->crime_charges)->sum('total');
        }

//        dd($query_region_wise->sum('total'));

        $total_grand_value = $query_region_wise->sum('total');

        return view('report.index', compact('region_wise', 'grand_total', 'total_grand_value'));

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

        $query = DB::table(DB::raw('(SELECT prisoner_charges.crime_charges,
        COUNT(DISTINCT prisoner_charges.prisoner_id) AS total FROM prisoner_charges
        INNER JOIN prisoners ON prisoner_charges.prisoner_id = prisoners.id WHERE
        prisoners.status IN("Undertrial", "Sentenced", "Death Sentenced") AND prisoners.case_closed = "No"
        GROUP BY prisoner_charges.prisoner_id) as x'))
            ->select('crime_charges', DB::raw('count(total) as total'))
            ->groupBy('crime_charges')
            ->get();

        foreach ($query as $item) {
            $crime_wise[$item->crime_charges] = $item->total;
        }

        $total = $query->sum('total');


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
            ->select('prison', DB::raw('count(*) as prisoners'))
            ->whereIn('status', ['Undertrial', 'Sentenced', 'Death Sentenced'])
            ->where('case_closed', '=', 'No')
            ->groupBy('prison')
            ->get();


        foreach ($query as $item) {
            if ($item->prison == null) {
                $prison_wise['NotSet'] = $item->prisoners;
            } else {
                $prison_wise[$item->prison] = $item->prisoners;
            }
        }

//        dd($prison_wise);

        $total = $query->sum('prisoners');

        return view('report.prisonWise', compact('prison_wise', 'total'));
    }

    public function regionWise()
    {
        $region_wise = [];
        foreach (Prison::all() as $item) {
            $region_wise[$item->region]['Undertrial'] = 0;
            $region_wise[$item->region]['Sentenced'] = 0;
            $region_wise[$item->region]['Death Sentenced'] = 0;
            $region_wise['NotSet']['Undertrial'] = 0;
            $region_wise['NotSet']['Sentenced'] = 0;
            $region_wise['NotSet']['Death Sentenced'] = 0;
        }

//        dd($region_wise);

        $query = DB::table('prisoners')
            ->select('region', 'status', DB::raw('count(*) as total'))
            ->whereIn('status', ['Undertrial', 'Sentenced', 'Death Sentenced'])
            ->where('case_closed', '=', 'No')
            ->groupBy('region', 'status')
            ->get();
        foreach ($query as $item) {
            if ($item->region == null) {
                $region_wise['NotSet'][$item->status] = $item->total;
            } else {
                $region_wise[$item->region][$item->status] = $item->total;
            }
        }

        $total = $query->sum('total');

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
