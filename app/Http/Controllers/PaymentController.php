<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
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
}
