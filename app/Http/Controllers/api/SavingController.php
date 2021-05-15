<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Saving;
use App\Models\SavingDetail;
use App\Models\Shift;
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
        return $saving->load('items', 'details', 'customer', 'team', 'user');
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
        //
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
            $detail = new SavingDetail( $request->input('deposit') );
            $detail['dt'] = jsDateToDateTimeString($detail['dt']);
            $saving->details()->save($detail);

            $payments = $request->input('payments');

            foreach ($payments as $payment) {
                $p = new Payment();
                $p->parse($payment);
                $p->payment_type_id = 'dep';
                $saving->payments()->save($p);
                $detail->payments()->save($p);
            }

            $saving->updateTotal();
            $saving->save();
            $saving->refresh();



        });
        return $saving->refresh();

    }
}
