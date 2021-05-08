<?php

namespace App\Http\Controllers;

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

                //todo stockcard work
                foreach ($sale->details as $detail) {
                    $stock = StockCard::whereProductId($detail->product_id)->first();
                    $stock = $stock->replicate();
                    $stock->fill([
                    ]);
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
