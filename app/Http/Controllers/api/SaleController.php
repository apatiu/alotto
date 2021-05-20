<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
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
                $data['dt'] = jsDateToDateTimeString($data['dt']);
                $data['team_id'] = $request->user()->currentTeam->id;
                $data['user_id'] = $request->user()->id;
                $data['status'] = 'checked';
                $sale = Sale::create($data);

                if (in_array($data['type'], ['sale', 'change'])) {
                    $sale->details()->createMany($data['sales']);
                }

                if (in_array($data['type'], ['buy', 'change']))
                    $sale->details()->createMany($data['buys']);

                //relation work
                $shift = Shift::current();
                //payment work
                foreach ($data['payments'] as $payment) {
                    $model = new Payment();
                    $model->parse($payment);
                    $model->payment_type_id = $data['type'];
                    $sale->payments()->save($model);
                }

                //stock work
                foreach ($sale->details as $detail) {
                    if ($detail->status === 'sale') {
                        $latest = StockCard::whereProductId($detail->product_id)
                            ->latest('dt')->first();

                            $new = new StockCard();
                            $new = $latest->replicate();
                            $new->fill([
                                'dt' => $sale->dt,
                                'qty_begin' => $latest->qty_end,
                                'qty_in' => 0,
                                'qty_out' => $detail->qty,
                                'qty_end' => $latest->qty_end - $detail->qty,
                                'wt_begin' => $latest->wt_end,
                                'wt_in' => 0,
                                'wt_out' => $detail->wt,
                                'wt_end' => $latest->wt_end - $detail->wt,
                                'user_id' => $sale->user_id,
                                'ref_id' => $sale->id,
                                'ref_type' => Sale::class,
                                'description' => 'ขาย'
                            ]);
                            $new->save();

                    } elseif ($detail->status === 'buy') {

                        $row = new OldGoldStockCard([
                            'dt' => $sale->dt,
                            'gold_percent_id' => $detail->product_percent_id,
                            'team_id' => $sale->team_id,
                            'avg_per_baht' => $detail->avg_per_baht,
                            'ref_id' => $sale->id,
                            'ref_type' => Sale::class,
                            'qty_begin' => 0,
                            'qty_in' => 1,
                            'qty_end' => 1,
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
                            $row->qty_begin = $stock->qty_end;
                            $row->qty_in = $detail->qty;
                            $row->qty_end = $stock->qty_end + $detail->qty;
                            $row->wt_begin = $stock->wt_end;
                            $row->wt_in = $detail->wt;
                            $row->wt_end = $stock->wt_end + $detail->wt;
                            $row->save();
                        }

                        $shift['old_gold_' . $detail->product_percent_id] += $detail->wt;
                        $shift->save();
                    }
                }

            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return Sale::whereId($sale->id)->with(['payments.method','payments.bank_account'])->first();
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

    public function printGuaranteeCard(Sale $sale) {
        return view('sales.print.guarantee_card', compact('sale'));
    }
}
