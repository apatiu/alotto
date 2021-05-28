<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MetaHelper;
use App\Models\IntrDiscountRate;
use App\Models\IntrRangeRate;
use App\Models\Media;
use App\Models\OldGoldStockCard;
use App\Models\Pawn;
use App\Models\PawnIntReceive;
use App\Models\Payment;
use App\Models\Shift;
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
        $pawn = new Pawn();
        DB::transaction(function () use ($request, $pawn) {
            $pawn->team_id = $request->user()->currentTeam->id;
            $pawn->dt = jsDateToDateTimeString(request('dt'));
            $pawn->dt_end = jsDateToDateTimeString(request('dt_end'));
            $pawn->customer_id = request('customer_id');
            $pawn->price = request('price');
            $pawn->status = 'new';
            $pawn->int_rate = request('int_rate');
            $pawn->int_per_month = $pawn->price * $pawn->int_rate / 100;
            $pawn->life = request('life');

            $pawn->save();

            foreach ($request->input('items', []) as $item) {
                if (is_array($item['product_type'])) {
                    $item['product_type'] = $item['product_type']['name'];
                }
                $pawnitem = $pawn->items()->create($item);

                if (isset($item['img'])) {
                    $media = new Media([
                        'type' => 'base64',
                        'datatext' => $item['img']
                    ]);
                    $pawnitem->images()->save($media);
                }
            }

            foreach (request('payments', []) as $payment) {
                $payment['amount'] = 0 - abs($payment['amount']);
                $pm = new Payment();
                $pm->parse($payment, 'paw');
                $pawn->payments()->save($pm);
            }
        });
        return $pawn;
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
    public function search(Request $request)
    {
        $data = Pawn::with(['customer', 'team']);
        $filters = [
            'status' => $request->input('status', 'new,int')
        ];

        if ($request->input('dt_end_over', 0) > 0) {
            $data->where(
                'dt_end',
                '<=',
                now()->addDays(0 - $filters['dt_end_over'])->toDateString());
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

        $data->whereIn('status', explode(',', $request->input('status', 'new,int')));


        $pagination = request('pagination', [
            'rowsPerPage' => 12
        ]);

        return $data->paginate($pagination['rowsPerPage']);
    }

    public function edit(Pawn $pawn)
    {
        //
    }

    public function getTodayInt(Pawn $pawn)
    {
        $latestInt = PawnIntReceive::wherePawnId($pawn->id)->latest('dt_end')->first();
        $dt = Carbon::parse($latestInt ? $latestInt->dt_end : $pawn->dt);
        $diff = $dt->diff(now()->setTime(0, 0));

        $months = ($diff->y * 12) + $diff->m;
        $days = $diff->d + 1;
        $intPerMonth = $pawn->price * $pawn->int_rate / 100;
        $int_days = $intPerMonth;

        $comments = [];

        if (MetaHelper::get('pawn_int_discount_rates_enable', false)) {
            $discounts = IntrDiscountRate::orderBy('days')->where('days', '>=', $days)->first();
            if ($discounts) {
                $int_days = ceil($intPerMonth * $discounts->rate / 100);
                array_push($comments, 'ได้รับส่วนลดดอกเบี้ยตามจำนวนวัน');
            }
        }

        $int = ($intPerMonth * $months) + $int_days;

        $int_min = MetaHelper::get('pawn_int_min', 0);

        $int = ($int_min > $int) ? $int_min : $int;

        $output = [
            'dt_start' => $dt->toDateTimeString(),
            'months' => $months,
            'days' => $days,
            'int' => $int,
            'comments' => $comments
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
        DB::transaction(function () use ($pawn) {

            $data = request()->except([
                'id', 'items', 'int_receives', 'prev_id', 'next_id', 'team_id']);
            $data['dt'] = jsDateToDateString($data['dt']);
            $data['dt_end'] = jsDateToDateString($data['dt_end']);
            $pawn->update($data);

            foreach ($pawn->items as $item) {
                $item->images()->delete();
            }
            $pawn->items()->delete();
            foreach (request('items', []) as $item) {
                $pawnitem = $pawn->items()->create($item);

                if (isset($item['img'])) {
                    $media = new Media([
                        'type' => 'base64',
                        'datatext' => $item['img']
                    ]);
                    $pawnitem->images()->save($media);
                }
            }
        });

        return $pawn;
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

    public function storeAction(Request $request, Pawn $pawn)
    {
        $team_id = request()->user()->currentTeam->id;

        if (request('type') == 'int') {
            DB::transaction(function () use ($pawn) {
                $pawn->dt_end = Carbon::parse(request('dt_end'))
                    ->addMonths($pawn->life)
                    ->toDateString();
                $pawn->status = 'int';
                $pawn->save();


                $int = $pawn->int_receives()->create([
                    'dt' => jsDateToDateTimeString(request('dt')),
                    'dt_end' => jsDateToDateTimeString(request('dt_end')),
                    'amount' => request('amount'),
                    'month_pay' => request('months')
                ]);

                foreach (request('payments') as $payment) {
                    $p = new Payment([
                        'team_id' => request()->user()->currentTeam->id,
                        'payment_no' => 0,
                        'shift_id' => Shift::current()->id,
                        'dt' => jsDateToDateTimeString($payment['dt']),
                        'receive' => $payment['amount'],
                        'method_id' => $payment['method_id'],
                        'payment_type_id' => 'int'
                    ]);
                    $pawn->payments()->save($p);
                    $int->payments()->attach($p);
                }

            });
            return $pawn->load(['items', 'payments', 'customer', 'int_receives']);
        } elseif (request('type') == 'red') {

            DB::transaction(function () use ($pawn, $team_id) {
                $intReceive = null;
                $pawn->status = 'red';
                $pawn->save();

                if (request('int') > 0) {
                    $int = $pawn->int_receives()->create([
                        'dt' => jsDateToDateTimeString(request('dt')),
                        'dt_end' => jsDateToDateTimeString(request('dt_end')),
                        'amount' => request('int'),
                    ]);
                }

                foreach (request('payments') as $payment) {
                    $p = new Payment([
                        'team_id' => $team_id,
                        'payment_no' => 0,
                        'shift_id' => Shift::current()->id,
                        'dt' => jsDateToDateTimeString($payment['dt']),
                        'receive' => $payment['amount'],
                        'method_id' => $payment['method_id'],
                        'payment_type_id' => 'red'
                    ]);

                    $pawn->payments()->save($p);
                    $int->payments()->attach($p);
                }
            });
            return $pawn->load(['items', 'payments', 'customer', 'int_receives']);
        } elseif (request('type') == 'chg') {
            $newPawn = null;
            DB::transaction(function () use ($pawn, &$newPawn, $team_id) {
                $intReceive = null;
                $pawn->status = 'chg';
                $pawn->save();

                if (request('int') > 0) {
                    $int = $pawn->int_receives()->create([
                        'dt' => jsDateToDateTimeString(request('dt')),
                        'dt_end' => jsDateToDateTimeString(request('dt_ned')),
                        'amount' => request('int'),
                    ]);
                }

                foreach (request('payments') as $payment) {
                    $p = new Payment([
                        'team_id' => $team_id,
                        'payment_no' => 0,
                        'shift_id' => Shift::current()->id,
                        'dt' => jsDateToDateTimeString($payment['dt']),
                        'method_id' => $payment['method_id'],
                        'payment_type_id' => request('isMore') ? 'mor' : 'les'
                    ]);

                    if (request('isMore')) {
                        $p->pay = $payment['amount'];
                    } else {
                        $p->receive = $payment['amount'];
                    }

                    $pawn->payments()->save($p);
                    $int->payments()->attach($p);
                }

                $newPawn = $pawn->replicate();
                $newPawn->price = request('newPrice');
                $newPawn->prev_id = $pawn->id;
                $newPawn->status = 'new';
                $newPawn->push();

                $pawn->refresh();
                foreach ($pawn->items as $item) {
                    $newItem = $newPawn->items()->create($item->replicate()->toArray());
                    foreach ($item->images as $image) {
                        $newImage = new Media($image->replicate()->toArray());
                        $newItem->images()->save($newImage);
                    }
                }


                $avgPricePerGram = $newPawn->price / $newPawn->weight;
                foreach ($newPawn->items as $item) {
                    $item->price = $avgPricePerGram * $item->weight;
                    $item->save();
                }

                $pawn->next_id = $newPawn->id;
                $pawn->save();
            });
            return $newPawn->load(['items', 'payments', 'customer', 'int_receives']);
        } elseif (request('type') == 'cut') {
            $pawn = $this->cut($pawn);
            return $pawn->load(['items', 'payments', 'customer', 'int_receives']);
        }


    }

    public function cut(Pawn $pawn)
    {

        DB::transaction(function () use ($pawn) {
            $pawn->status = 'cut';
            $pawn->save();

            foreach ($pawn->items as $item) {
                $sc = OldGoldStockCard::add(
                    $item->gold_percent_id,
                    1,
                    $item->weight,
                    $item->price,
                    $pawn,
                    'คัดออก',
                    $pawn->team_id);
            }
        });


        return $pawn;
    }

    public function print_ticket(Pawn $pawn)
    {
        return view('pawns.ticket', compact('pawn'));

    }
}
