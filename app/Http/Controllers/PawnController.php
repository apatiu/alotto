<?php

namespace App\Http\Controllers;

use App\Http\Helpers\MetaHelper;
use App\Models\Customer;
use App\Models\IntDiscountRate;
use App\Models\IntRangeRate;
use App\Models\Pawn;
use App\Models\Payment;
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
        return Inertia::render('Pawns/Index', [
            'items' => Pawn::with(['customer'])->get()
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pawn = new Pawn();
        DB::transaction(function () use ($request, $pawn) {
            $pawn->team_id = $request->user()->currentTeam->id;
            $pawn->dt = request('dt');
            $pawn->dt_end = request('dt_end');
            $pawn->customer_id = request('customer_id');
            $pawn->price = request('price');
            $pawn->status = 'new';
            $pawn->int_rate = request('int_rate');
            $pawn->life = request('life');

            $pawn->save();

            $pawn->items()->createMany($request->input('items'));
            $payment = new Payment([
                'team_id' => $pawn->team_id,
                'payment_no' => '',
                'dt' => $pawn->dt,
                'payment_type_id' => 'paw',
                'method' => 'cash'
            ]);
            $payment->id = $payment->gen_id($pawn->dt);
//            $payment->save();

            $pawn->payments()->save($payment);
        });
        return $pawn->load(['items', 'payments', 'customer', 'int_receives']);
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
                $payment = new Payment([
                    'team_id' => request()->user()->currentTeam->id,
                    'payment_no' => 0,
                    'dt' => jsDateToSql(request('dt')),
                    'receive' => request('amount'),
                    'method' => 'cash'
                ]);
                $payment->id = $payment->gen_id(jsDateToSql(request('dt')));
                $pawn->payments()->save($payment);
            });
        }

        return $pawn->load(['items', 'payments', 'customer', 'int_receives']);
    }

    public function print_ticket(Pawn $pawn)
    {
        return view('pawns.ticket', compact('pawn'));

    }
}
