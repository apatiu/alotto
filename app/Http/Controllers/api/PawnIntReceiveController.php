<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PawnIntReceive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PawnIntReceiveController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PawnIntReceive $PawnIntReceive
     * @return \Illuminate\Http\Response
     */
    public function show(PawnIntReceive $PawnIntReceive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PawnIntReceive $PawnIntReceive
     * @return \Illuminate\Http\Response
     */
    public function edit(PawnIntReceive $PawnIntReceive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PawnIntReceive $PawnIntReceive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PawnIntReceive $PawnIntReceive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PawnIntReceive $PawnIntReceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(PawnIntReceive $PawnIntReceife)
    {
        DB::transaction(function () use ($PawnIntReceife) {
            $dt_end = $PawnIntReceife->pawn->dt_end;
            $dt_end = Carbon::parse($dt_end);
            $dt_end->addMonths(0 - $PawnIntReceife->month_pay);
            $PawnIntReceife->pawn()->update(['dt_end' => $dt_end->toDateTimeString()]);
            $PawnIntReceife->delete();
        });

    }
}
