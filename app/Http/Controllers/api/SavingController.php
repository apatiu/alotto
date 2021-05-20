<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoldPrice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Saving;
use App\Models\SavingDetail;
use App\Models\Shift;
use App\Models\StockCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SavingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $saving = new Saving();
        DB::transaction(function () use ($request, $saving) {
            $saving->fill($request->all());
            $saving->team_id = $request->user()->currentTeam->id;
            $saving->dt = jsDateToDateTimeString(request('dt'));
            $saving->dt_due = jsDateToDateTimeString(request('dt_due'));
            $saving->status = 'open';
            $saving->save();

            $saving->items()->createMany($request->input('items', []));
        });
        return $saving;
    }

    public function show(Saving $saving)
    {
        return $saving->load('items', 'items.product', 'details', 'customer', 'team', 'user');
    }


    public function search(Request $request)
    {
        $data = Saving::with(['items', 'details', 'customer', 'team', 'user']);
        $filters = [
            'status' => $request->input('status', 'open')
        ];

        if ($request->input('dt_due_over', 0) > 0) {
            $data->where(
                'dt_due',
                '<=',
                now()->addDays(0 - $filters['dt_due_over'])->toDateString());
        }

        if ($request->input('q', false)) {
            $id = intval($request->input('q', ''));
            $data->where(function ($q) use ($request, $id) {
                $q->where('id', '=', $id);
                $q->orWhereHas('customer', function ($query) use ($request) {
                    return $query->where('name', 'like', "%{$request->input('q')}%");
                });
            });
        }

        $data->whereIn('status', explode(',', $request->input('status', 'open')));


        $pagination = request('pagination', [
            'rowsPerPage' => 12
        ]);

        return $data->paginate($pagination['rowsPerPage']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Saving $saving
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Saving $saving)
    {
        DB::transaction(function () use ($request, $saving) {
            $saving->fill($request->all());
            $saving->team_id = $request->user()->currentTeam->id;
            $saving->dt = jsDateToDateTimeString(request('dt'));
            $saving->dt_due = jsDateToDateTimeString(request('dt_due'));
            $saving->status = 'open';
            $saving->save();

            $saving->items()->delete();
            $saving->items()->createMany($request->input('items', []));
        });
        return $saving->load('items', 'items.product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Saving $saving
     * @return \Illuminate\Http\Response
     */
    public function destroy(Saving $saving)
    {

    }

    public function deposit(Request $request, Saving $saving)
    {
        DB::transaction(function () use ($saving, $request) {
            $detail = new SavingDetail($request->input('deposit'));
            $detail['dt'] = jsDateToDateTimeString($detail['dt']);
            $saving->details()->save($detail);

            $payments = $request->input('payments');

            foreach ($payments as $payment) {
                $p = new Payment();
                $p->parse($payment);
                $p->payment_type_id = 'saving-dep';
                $saving->payments()->save($p);
                $detail->payments()->save($p);
            }

            $saving->updateTotal();
            $saving->save();
            $saving->refresh();


        });
        return $saving->refresh();

    }

    public function refund(Request $request, Saving $saving)
    {
        $saving->fill($request->all());
        $saving->status = 'close';
        $saving->save();
        return true;
    }

    public function close(Request $request, Saving $saving)
    {
        try {
            DB::beginTransaction();

            $data = request('data');
            $payments = request('payments');

            //save saving row
            if ($data['type'] === 'forward') {
                $saving->price_forward = $data['price_forward'];
                $saving->dt_close = now();
                $saving->status = 'close';
                $saving->save();

                // create new saving if forward
                $new = new Saving();
                $new->customer_id = $saving->customer_id;
                $new->team_id = $saving->team_id;
                $new->gold_price_sale = goldprice();
                $new->dt = now();
                $new->price_pay = $data['price_forward'];
                $new->status = 'open';
                $new->user_id = $request->user()->id;
                $new->prev_id = $saving->id;
                $new->save();

                $saving->next_id = $new->id;
                $saving->save();

                //create new sale detail
                $new->details()->create([
                    'amount' => $data['price_forward'],
                    'dt' => $new->dt,
                    'gold_price_sale' => $new->gold_price_sale,
                    'is_forward' => true
                ]);

            } elseif ($data['type'] === 'refund') {  // refund
                $saving->price_refund = $data['price_refund'];
                $saving->dt_close = now();
                $saving->status = 'close';
                $saving->save();

                // create new payment if refund
                foreach ($payments as $payment) {
                    $p = new Payment();
                    $p->parse($payment);
                    $p->payment_type_id = 'saving-refund';
                    $saving->payments()->save($p);
                }

            } else { //close
                $saving->status = 'close';
                $saving->save();
            }

            // create sale record
            $sale = new Sale();
            $sale->fill(GoldPrice::now()->toArray());
            $sale->customer_id = $saving->customer_id;
            $sale->team_id = $saving->team_id;
            $sale->dt = now();
            $sale->type = 'sale';
            $sale->status = 'checked';
            $sale->save();
            $saving->sales()->save($sale);

            //create sale detail record
            $eachDeposit = $saving->price_total / $saving->items->count();
            foreach ($saving->refresh()->items as $item) {
                $product = Product::find($item->product_id);

                $saleDetail = new SaleDetail();
                $saleDetail->fill($product->toArray());
                $saleDetail->qty = 1;
                $saleDetail->deposit = $eachDeposit;

                if ($saleDetail->sale_with_gold_price) {
                    $saleDetail->price_sale_wage = $saleDetail->deposit - $saleDetail->price_sale_gold;
                    $saleDetail->price_sale_total = $saleDetail->deposit - $saleDetail->price_sale_gold - $saleDetail->price_sale_wage;
                }

                $sale->details()->save($saleDetail);

                // create stock card
                $card = StockCard::add($item->product, 0 - $item->qty, $saving);

            }
            $sale->refresh();
            $sale->recalcTotal();
            $sale->createPayments();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }
}
