<?php

namespace App\Http\Controllers;

use App\Models\OldGoldStockCard;
use App\Models\StockCard;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $rows = StockCard::with(['product','ref']);

        $pagination = request('pagination',[
            'rowsPerPage'=> 12
        ]);

        return Inertia::render('StockCards/Index', [
            'rows' => $rows->paginate($pagination['rowsPerPage']),
            'pagination' => $pagination
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
     * @param  \App\Models\StockCard  $stockCard
     * @return \Illuminate\Http\Response
     */
    public function show(StockCard $stockCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockCard  $stockCard
     * @return \Illuminate\Http\Response
     */
    public function edit(StockCard $stockCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockCard  $stockCard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCard $stockCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockCard  $stockCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockCard $stockCard)
    {
        //
    }
}
