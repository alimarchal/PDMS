<?php

namespace App\Http\Controllers;

use App\Models\CrimeCharges;
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
            foreach (CrimeCharges::all() as $item_2) {
                if (!empty($item->jail)) {
                    $region_wise[$item->region][$item->jail][$item_2->name] = 0;
                    $grand_total[$item_2->name] = 0;
                }
            }
        }


        $query_region_wise = DB::table('prisoners')
            ->select('prisoners.region', 'prisoners.prison', 'prisoner_charges.crime_charges', DB::raw('COUNT(prisoner_charges.prisoner_id) AS total'))
            ->join(DB::raw('(SELECT MAX(id) AS id, prisoner_id FROM prisoner_charges GROUP BY prisoner_id) AS latest_charges'), 'prisoners.id', '=', 'latest_charges.prisoner_id')
            ->join('prisoner_charges', function($join) {
                $join->on('latest_charges.id', '=', 'prisoner_charges.id');
            })
            ->whereIn('prisoners.status', ['Undertrial', 'Sentenced', 'Death Sentenced'])
            ->whereNotNull('prisoners.prison')
            ->where('prisoners.case_closed', '=', 'No')
            ->groupBy('prisoners.region', 'prisoners.prison', 'prisoner_charges.crime_charges')
            ->orderBy('total', 'ASC')
            ->get();


        foreach ($query_region_wise as $item) {
            $region_wise[$item->region][$item->prison][$item->crime_charges] = $item->total;
            $grand_total[$item->crime_charges] = $query_region_wise->where('crime_charges', $item->crime_charges)->sum('total');
        }
        $total_grand_value = $query_region_wise->sum('total');
//        dd($query_region_wise);
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
        prisoners.status IN("Undertrial", "Sentenced", "Death Sentenced") AND prisoners.case_closed = "No" AND prisoners.prison is not null
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
        foreach (Prison::whereNotNull('jail')->get() as $item) {
            $prison_wise[$item->jail] = 0;
        }

//        $prison_wise['NotSet'] = 0;

        $query = DB::table('prisoners')
            ->select('prison', DB::raw('count(*) as prisoners'))
            ->whereIn('status', ['Undertrial', 'Sentenced', 'Death Sentenced'])
            ->where('case_closed', '=', 'No')
            ->whereNotNull('prison')
            ->groupBy('prison')
            ->get();


//        dd($prison_wise);

        foreach ($query as $item) {
//            if ($item->prison == null) {
//                $prison_wise['NotSet'] = $item->prisoners;
//            } else {
//                $prison_wise[$item->prison] = $item->prisoners;
//            }
            $prison_wise[$item->prison] = $item->prisoners;
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
//            $region_wise['NotSet']['Undertrial'] = 0;
//            $region_wise['NotSet']['Sentenced'] = 0;
//            $region_wise['NotSet']['Death Sentenced'] = 0;
        }

//        dd($region_wise);

        $query = DB::table('prisoners')
            ->select('region', 'status', DB::raw('count(*) as total'))
            ->whereIn('status', ['Undertrial', 'Sentenced', 'Death Sentenced'])
            ->where('case_closed', '=', 'No')
            ->whereNotNull('prison')
            ->groupBy('region', 'status')
            ->get();
        foreach ($query as $item) {
//            if ($item->region == null) {
//                $region_wise['NotSet'][$item->status] = $item->total;
//            } else {
//                $region_wise[$item->region][$item->status] = $item->total;
//            }

            $region_wise[$item->region][$item->status] = $item->total;
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
