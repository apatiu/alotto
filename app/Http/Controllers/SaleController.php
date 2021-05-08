<?php

namespace App\Http\Controllers;

use App\Models\OldGoldStockCard;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Shift;
use App\Models\StockCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
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
        try {
            DB::beginTransaction();
            $data = $request->all();
            if ($data['id'])
                $this->update($request, $data['id']);
            else {
                unset($data['id'], $data['code']);
                $data['dt'] = jsDateToDateString($data['dt']);
                $data['team_id'] = $request->user()->currentTeam->id;
                $data['status'] = 'checked';
                $sale = Sale::create($data);

                if (in_array($data['type'], ['sale', 'change'])) {
                    $sale->details()->createMany($data['sales']);
                }

                if (in_array($data['type'], ['buy', 'change']))
                    $sale->details()->createMany($data['buys']);

                //payment work
                foreach ($data['payments'] as $payment) {
                    $model = new Payment();
                    $model->fill($payment);
                    $model->fill([
                        'team_id' => $request->user()->currentTeam->id,
                        'shift_id' => Shift::current()->id,
                        'payment_no' => '',
                        'dt' => $sale->dt,
                        'payment_type_id' => $sale->type
                    ]);
                    if ($payment['amount'] < 0)
                        $model->pay = $payment['amount'];
                    else
                        $model->receive = $payment['amount'];

                    $sale->payments()->save($model);
                }

                foreach ($sale->details as $detail) {
                    if ($detail->status === 'sale') {
                        $stock = StockCard::whereProductId($detail->product_id)
                            ->latest()->first();
                        $stock = $stock->replicate();
                        $stock->fill([
                            'gold_percent_id' => $detail->product_percent_id,
                            'dt' => $sale->dt,
                            'qty_begin' => $stock->qty_end,
                            'qty_out' => $detail->qty,
                            'qty_end' => $stock->qty_end - $detail->qty,
                            'weight_begin' => $stock->weight_begin,
                            'weight_out' => $detail->wt,
                            'weight_end' => $stock->weight_end - $detail->wt,
                            'user_id' => $sale->user_id,
                            'ref_id' => $sale->id,
                            'ref_type' => Sale::class
                        ]);
                    } elseif ($detail->status === 'buy') {

                        $row = new OldGoldStockCard([
                            'dt' => $sale->dt,
                            'gold_percent_id' => $detail->product_percent_id,
                            'team_id' => $sale->team_id,
                            'avg_per_baht' => $detail->avg_per_baht,
                            'ref_id' => $sale->id,
                            'ref_type' => Sale::class,
                            'qty_begin' => 0,
                            'qty_in' => $detail->qty,
                            'qty_end' => $detail->qty,
                            'wt_begin' => 0,
                            'wt_in' => $detail->wt,
                            'wt_end' => $detail->wt,
                        ]);

                        if (OldGoldStockCard::whereGoldPercentId($detail->product_percent_id)
                                ->whereTeamId($sale->team_id)
                                ->count() === 0) {
                            $row->save();
                        } else {
                            $stock = OldGoldStockCard::whereGoldPercentId($detail->product_percent_id)
                                ->whereTeamId($sale->team_id)
                                ->first();
                            $row->qty_begin = $stock->qty_begin;
                            $row->qty_end = $row->qty_begin + $row->qty_in;
                            $row->wt_begin = $stock->wt_begin;
                            $row->wt_end = $row->wt_begin + $row->wt_begin;
                        }
                    }
                }

            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
