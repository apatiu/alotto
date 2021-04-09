<?php

namespace App\Http\Controllers;

use App\Models\OldGoldStockCard;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OldGoldStockCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $rows = OldGoldStockCard::with(['team','gold_percent','ref']);

        $pagination = request('pagination',[
            'rowsPerPage'=> 12
        ]);

        return Inertia::render('OldGoldStocks/Index', [
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
     * @param \App\Models\OldGoldStockCard $oldGoldStockCard
     * @return \Illuminate\Http\Response
     */
    public function show(OldGoldStockCard $oldGoldStockCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\OldGoldStockCard $oldGoldStockCard
     * @return \Illuminate\Http\Response
     */
    public function edit(OldGoldStockCard $oldGoldStockCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\OldGoldStockCard $oldGoldStockCard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OldGoldStockCard $oldGoldStockCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\OldGoldStockCard $oldGoldStockCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(OldGoldStockCard $oldGoldStockCard)
    {
        //
    }
}
