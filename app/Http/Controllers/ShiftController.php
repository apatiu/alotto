<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Shift;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShiftController extends Controller
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $shift = new Shift([
            'team_id' => $request->user()->currentTeam->id,
            'd' => now(),
            'cash_begin' => $request->input('cash_begin'),
            'cash' => 0,
            'cash_to_safe' => 0,
            'cash_to_bank' => 0,
            'cash_end' => 0,
            'bank' => 0,
            'card' => 0,
            'open_user_id' => $request->user()->id,
            'close_user_id' => 0,
            'opened_at' => now(),
            'status' => 'open'
        ]);
        $shift->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Shift $shift
     * @return \Inertia\Response
     */
    public function show(Shift $shift)
    {
        $shift->calc();
        return Inertia::render('Shifts/Show',[
            'shift'=> $shift->load('payments')
        ]);
    }

    public function showLatest()
    {
        $shift = $this->getLatestShift();
        $shift->calc();
        return Inertia::render('Shifts/Show',[
            'shift'=> $shift->load('payments')
        ]);
    }

    public function getLatestShift() {
        return Shift::whereTeamId(request()->user()->currentTeam->id)->latest()->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift $shift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift $shift)
    {
        $shift->fill($request->all());
        $shift->save();
        return redirect('/dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift $shift)
    {
        //
    }
}