<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportDayController extends Controller
{
    private $dBegin = null;
    private $dEnd = null;

    public function index($d1 = null, $d2 = null)
    {
        if (!$d1) {
            $this->dBegin = now()->startOfDay();
            $this->dEnd = now()->endOfDay();
        }

        $this->loadPayments();

        $data['payments'] = $this->loadPayments();


        return Inertia::render('Reports/Print', [
            'data' => $data
        ]);
    }

    private function loadPayments()
    {

        $data = Payment::with(['payment_type'])
            ->select('payment_type_id',
                DB::raw('SUM(receive) as total_receive'),
                DB::raw('SUM(pay) as total_pay'))
            ->groupBy('payment_type_id')
            ->whereBetween('dt',
                [$this->dBegin->toDateTimeString(), $this->dEnd->toDateTimeString()])
            ->get();
        return $data;
    }
}
