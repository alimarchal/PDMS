<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCrimeChargesRequest;
use App\Http\Requests\UpdateCrimeChargesRequest;
use App\Models\CrimeCharges;

class CrimeChargesController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCrimeChargesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCrimeChargesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrimeCharges  $crimeCharges
     * @return \Illuminate\Http\Response
     */
    public function show(CrimeCharges $crimeCharges)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CrimeCharges  $crimeCharges
     * @return \Illuminate\Http\Response
     */
    public function edit(CrimeCharges $crimeCharges)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCrimeChargesRequest  $request
     * @param  \App\Models\CrimeCharges  $crimeCharges
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCrimeChargesRequest $request, CrimeCharges $crimeCharges)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrimeCharges  $crimeCharges
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrimeCharges $crimeCharges)
    {
        //
    }
}
