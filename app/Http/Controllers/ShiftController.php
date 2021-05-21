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
        $shift = new Shift();
        $shift->fill(array_merge($request->all(), [
            'team_id' => $request->user()->currentTeam->id,
            'cash_begin' => $request->input('cash_begin'),
            'open_user_id' => $request->user()->id,
            'opened_at' => now(),
            'status' => 'open'
        ]));
//        dd($shift);
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
        return Inertia::render('Shifts/Show', [
            'shift' => $shift->load('payments')
        ]);
    }

    public function showLatest()
    {
        $shift = $this->getLatestShift();
        $shift->calc();
        return Inertia::render('Shifts/Show', [
            'shift' => $shift->load('payments')
        ]);
    }

    public function getLatestShift()
    {
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
