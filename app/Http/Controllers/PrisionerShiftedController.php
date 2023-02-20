<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrisionerShiftedRequest;
use App\Http\Requests\UpdatePrisionerShiftedRequest;
use App\Models\PrisionerShifted;
use App\Models\Prisoner;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PrisionerShiftedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Prisoner $prisoner)
    {
        return view('shifting.create', compact('prisoner'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePrisionerShiftedRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePrisionerShiftedRequest $request)
    {

        $prisoner = $request->prisoner_id;
        if ($request->input('shifting_date_hijri')) {
            if (strlen($request->shifting_date_hijri) >= 10) {
                $url = "http://api.aladhan.com/v1/hToG?date=" . Carbon::parse($request->shifting_date_hijri)->format('d-m-Y');
                $response = Http::get($url);
                $json_format = $response->json();
                $gg_date = $json_format['data']['gregorian']['date'];
                $date_in_gg = Carbon::createFromFormat('d-m-Y', $gg_date);
                $request->merge(['shifting_date_gregorian' => $date_in_gg->format('Y-m-d')]);
            }
        }
        PrisionerShifted::create([
            'prisoner_id' => $request->prisoner_id,
            'detention_authority' => $request->detention_authority,
            'detention_city' => $request->detention_city,
            'detention_place' => $request->detention_place,
            'shifted_to_other_department' => $request->shifted_to_other_department,
            'shifting_date_hijri' => $request->shifting_date_hijri,
            'other_details' => $request->other_details,
            'shifting_date_gregorian' => $request->shifting_date_gregorian,
        ]);
        session()->flash('message', 'Prisoner shifting information successfully updated.');
        return to_route('prisoner.show', $prisoner);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PrisionerShifted $prisionerShifted
     * @return \Illuminate\Http\Response
     */
    public function show(PrisionerShifted $prisionerShifted)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PrisionerShifted $prisionerShifted
     * @return \Illuminate\Http\Response
     */
    public function edit(PrisionerShifted $prisionerShifted)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePrisionerShiftedRequest $request
     * @param \App\Models\PrisionerShifted $prisionerShifted
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrisionerShiftedRequest $request, PrisionerShifted $prisionerShifted)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PrisionerShifted $prisionerShifted
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrisionerShifted $prisionerShifted)
    {
        //
    }
}
