<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MetaHelper;
use App\Models\IntDiscountRate;
use App\Models\IntRangeRate;
use App\Models\Pawn;
use App\Models\PawnIntReceives;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PawnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Pawn $pawn
     * @return Pawn
     */
    public function show(Pawn $pawn)
    {
        return $pawn->load(['items', 'customer', 'int_receives']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Pawn $pawn
     * @return \Illuminate\Http\Response
     */
    public function edit(Pawn $pawn)
    {
        //
    }

    public function getTodayInt(Pawn $pawn)
    {
        $latestInt= PawnIntReceives::wherePawnId($pawn->id)->latest('dt_end')->first();
        $dt = new Carbon( $latestInt ? $latestInt->dt_end : $pawn->dt);

        $diff = $dt->diff(now());
        $months = ($diff->y * 12) + $diff->m;
        $days = $diff->d + 1;
        $intPerMonth = $pawn->price * $pawn->int_rate / 100;
        $discounts = IntDiscountRate::orderBy('days')->where('days', '>=', $days)->first();
        if ($discounts) {
            $int_days = ceil($intPerMonth * $discounts->rate / 100);
        } else {
            $months++;
            $int_days = 0;
        }

        $output = [
            'dt_start' => $dt->toDateTimeString(),
            'months' => $months,
            'days' => $days,
            'int' => ($intPerMonth * $months) + $int_days
        ];
        return $output;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Pawn $pawn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pawn $pawn)
    {
        $pawn->update($request->except([
            'id', 'items', 'int_receives', 'prev_id', 'next_id', 'team_id']));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Pawn $pawn
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pawn $pawn)
    {
        //
    }

    public function storeConfig(Request $request)
    {
        MetaHelper::set('pawn_life', $request->input('pawn_life'));
        MetaHelper::set('pawn_int_default_rate', $request->input('int_default_rate'));
        MetaHelper::set('pawn_int_min', $request->input('int_min'));
        MetaHelper::set('pawn_int_range_rates_enable', $request->input('int_range_rates_enable'));
        MetaHelper::set('pawn_int_discount_rates_enable', $request->input('int_discount_rates_enable'));
        DB::transaction(function () use ($request) {
            DB::table('int_range_rates')->truncate();
            IntRangeRate::insert($request->input('int_range_rates'));
            DB::table('int_discount_rates')->truncate();
            IntDiscountRate::insert($request->input('int_discount_rates'));
        });

        return redirect()->back();
    }

    public function storeAction(Request $request, Pawn $pawn)
    {
        if ($request->input('type') == 'int') {
            DB::transaction(function () use ($pawn, $request) {
                $pawn->dt_end = jsDateToSql($request->input('dt_end'));
                $pawn->save();

                $pawn->int_receives()->create([
                    'dt' => jsDateToSql(request('dt')),
                    'dt_end' => jsDateToSql(request('dt_end')),
                    'amount' => request('amount')
                ]);
                foreach ($request->payments as $payment) {
                    $p = new Payment([
                        'team_id' => request()->user()->currentTeam->id,
                        'payment_no' => 0,
                        'dt' => $payment['dt'],
                        'receive' => $payment['amount'],
                        'method' => $payment['method'],
                        'payment_type_id' => 'int'
                    ]);
                    $pawn->payments()->save($p);
                }

            });
        }

        return $pawn->load(['items', 'payments', 'customer', 'int_receives']);
    }

    public function print_ticket(Pawn $pawn)
    {
        return view('pawns.ticket', compact('pawn'));

    }
}
