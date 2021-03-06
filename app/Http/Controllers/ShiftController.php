<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Shift;
use Carbon\Traits\Creator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
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
        return 'Shift index';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $shift = new Shift();
        $team = request()->user()->currentTeam;
        $latest = Shift::whereTeamId($team->id)->latest()->first();
        if ($latest) {

            if ($latest->status === 'close') {
                $shift->cash_begin = $latest->cash_end;
            } else {
                $this->show($latest);
            }
        } else {
            $shift->cash_begin = 0;
        }

        $shift->team_id = $team->id;
        $shift->status = 'open';
        $shift->load('team');

        return Inertia::render('Shifts/Create', [
            'shift' => $shift
        ]);
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
        $shift->fill( [
            'team_id' => $request->user()->currentTeam->id,
            'cash_begin' => $request->input('cash_begin'),
            'open_user_id' => $request->user()->id,
            'opened_at' => now(),
            'status' => 'open'
        ]);
        $shift->save();
        return redirect('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Shift $shift
     * @return \Inertia\Response
     */
    public function show(Shift $shift)
    {

        $shift->load('payments', 'payments.method', 'payments.payment_type', 'payments.user');
        return Inertia::render('Shifts/Show', [
            'd' => $shift->d,
            'shift' => $shift
        ]);
    }

    public function showCurrent()
    {
        $shift = Shift::current();
        if ($shift) {
            $shift->calc();
            $shift->load('payments', 'payments.method', 'payments.payment_type', 'payments.user');
        }

        return Inertia::render('Shifts/Current', [
            'shift' => $shift
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
    public function close(Request $request, Shift $shift)
    {
        DB::transaction(function () use ($shift, $request) {
            $shift->fill($request->except('d'));
            $shift->closed_at = jsDateToDateTimeString(request('closed_at'));
            $shift->status = 'close';
//            $next_d = jsDateToDateTimeString($request->input('next_d'));
            $shift->save();
//            $this->createNextShift($next_d, $shift);
        });
        return Redirect::route('shifts.show', $shift->id);

    }

    private function createNextShift($d, Shift $old_shift)
    {

        $shift = new Shift();
        $shift->fill([
            'team_id' => Auth::user()->currentTeam->id,
            'd' => $d,
            'cash_begin' => $old_shift->cash_count,
            'cash_in' => 0,
            'cash_out' => 0,
            'cash_count' => 0,
            'cash_to_safe' => 0,
            'cash_to_bank' => 0,
            'cash_end' => 0,
            'bank_transfer_in' => 0,
            'bank_transfer_out' => 0,
            'bank_transfer_end' => 0,
            'card' => 0,
            'open_user_id' => Auth::user()->id,
            'opened_at' => now(),
            'status' => 'open'
        ]);

        return $shift->save();
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
