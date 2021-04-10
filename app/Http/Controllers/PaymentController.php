<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index() {
        return Inertia::render('Payments/Index',[
            'payments'=> Payment::with('team','payment_type','paymentable')->get()
        ]);
    }
}
