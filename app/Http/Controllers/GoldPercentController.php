<?php

namespace App\Http\Controllers;

use App\Models\GoldPercent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoldPercentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('GoldPercents/Index',[
            'items' => GoldPercent::all()
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GoldPercent  $goldPercent
     * @return \Illuminate\Http\Response
     */
    public function show(GoldPercent $goldPercent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GoldPercent  $goldPercent
     * @return \Illuminate\Http\Response
     */
    public function edit(GoldPercent $goldPercent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GoldPercent  $goldPercent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoldPercent $goldPercent)
    {
        $goldPercent->fill($request->all())->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GoldPercent  $goldPercent
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoldPercent $goldPercent)
    {
        //
    }
}
