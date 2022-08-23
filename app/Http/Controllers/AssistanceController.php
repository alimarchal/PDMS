<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistanceRequest;
use App\Http\Requests\UpdateAssistanceRequest;
use App\Models\Assistance;
use App\Models\JailOfficial;
use App\Models\Prisoner;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AssistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prison = QueryBuilder::for(Assistance::class)
            ->allowedFilters([
                AllowedFilter::scope('search_string'),
                AllowedFilter::scope('search_assistance'),
                AllowedFilter::exact('prisoner_id'),
                AllowedFilter::exact('type'),
            ])->latest()->paginate(50)->withQueryString();
        return view('assistance.index', compact('prison'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Prisoner $prisoner)
    {
        return view('assistance.create', compact('prisoner'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreAssistanceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssistanceRequest $request)
    {

        if ($request->hasFile('attachment_1')) {
            $path = $request->file('attachment_1')->store('', 'public');
            $request->merge(['attachment' => $path]);
        }

        $prisoner = $request->prisoner_id;
        $prison = Assistance::create($request->all());
        session()->flash('message', 'Legal / Counselor information successfully saved.');
        return to_route('prisoner.show', $prisoner);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Assistance $assistance
     * @return \Illuminate\Http\Response
     */
    public function show(Assistance $assistance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Assistance $assistance
     * @return \Illuminate\Http\Response
     */
    public function edit(Assistance $assistance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateAssistanceRequest $request
     * @param \App\Models\Assistance $assistance
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssistanceRequest $request, Assistance $assistance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Assistance $assistance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assistance $assistance)
    {
        $assistance->delete();
        session()->flash('message', 'Jail Official information successfully destroy.');
        return to_route('assistance.index');
    }
}
