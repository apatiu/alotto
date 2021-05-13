<?php

namespace App\Http\Controllers;

use App\Http\Helpers\MetaHelper;
use App\Models\Customer;
use App\Models\IntDiscountRate;
use App\Models\IntRangeRate;
use App\Models\Media;
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
     * @return \Inertia\Response
     */
    public function index()
    {
        $data = Pawn::with(['customer', 'team']);
        $filters = [];

        if (request()->has('filters')) {
            $filters = request('filters');
        } else {
            $filters = [
                'status' => 'new,int'
            ];
        }

        if ($filters['dt_end_over'] ?? 0 > 0) {
            $data->where(
                'dt_end',
                '<=',
                now()->addDays(0 - $filters['dt_end_over'])->toDateString());
        }

        if (isset($filters['q'])) {
            $data->where(function ($q) use ($filters) {
                $q->where('id', 'like', "%{$filters['q']}%");
                $q->orWhereHas('customer', function ($query) use ($filters) {
                    return $query->where('name', 'like', "%{$filters['q']}%");
                });
            });
        }

        if ($filters['status']) {
            $data->whereIn('status', explode(',', $filters['status']));
        }


        $pagination = request('pagination', [
            'rowsPerPage' => 12
        ]);
//        dd($data->toSql());
        return Inertia::render('Pawns/Index', [
            'filters' => $filters,
            'pagination' => $pagination,
            'data' => $data->paginate($pagination['rowsPerPage']),
            'new_id' => session('new-pawn-id', 0)
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
                $pawnitem = $pawn->items()->create($item);

                if (isset($item['img'])) {
                    $media = new Media([
                        'type' => 'base64',
                        'datatext' => $item['img']
                    ]);
                    $pawnitem->images()->save($media);
                }
            }

            $payment = new Payment([
                'team_id' => $pawn->team_id,
                'payment_no' => '',
                'dt' => $pawn->dt,
                'payment_type_id' => 'paw',
                'method' => 'cash',
                'pay' => $pawn->price
            ]);

            $pawn->payments()->save($payment);
        });
        return redirect()->back()->with('new-pawn-id', $pawn->id);
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
}
