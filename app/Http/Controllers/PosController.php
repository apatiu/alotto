<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PosController extends Controller
{
    public function index() {
        return Inertia::render('Pos/Index',[
            'bill' => null,
        ]);
    }

}
