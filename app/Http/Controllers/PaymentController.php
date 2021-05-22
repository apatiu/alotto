<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        $d = jsDateToDateString(urldecode(request('d', null)));
        if (!$d)
            $d = now();

        $rows = Payment::with('team', 'payment_type','method')
            ->orderBy('dt')
            ->whereDate('dt', $d);
        return Inertia::render('Payments/Index', [
            'd' => $d,
            'payments' => $rows->get()
        ]);
    }

    public function store(Request $request) {
        DB::transaction(function() use ($request) {
            $payment = new Payment();
            $payment->parse($request->all());
            $payment->save();
        });
        return redirect()->back();
    }
}
