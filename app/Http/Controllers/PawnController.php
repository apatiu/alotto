<?php

namespace App\Http\Controllers;

use App\Http\Helpers\MetaHelper;
use App\Models\Customer;
use App\Models\IntDiscountRate;
use App\Models\IntRangeRate;
use App\Models\Pawn;
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
        $customers = Customer::latest()->take(15);
        if (request()->has('filterCustomers')) {
            $customers = Customer::where('name', 'like', '%' . request('filterCustomers') . '%');
        }
        return Inertia::render('Pawns/Index', [
            'items' => Pawn::all(),
            'customers' => $customers->get()

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Pawn $pawn
     * @return \Illuminate\Http\Response
     */
    public function show(Pawn $pawn)
    {
        //
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
        //
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
}
