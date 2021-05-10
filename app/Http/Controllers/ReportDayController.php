<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportDayController extends Controller
{
    public function index($d = null)
    {
        if (!$d)
            $d = now();

        $data = 'report';
        return Inertia::render('Reports/Print',[
            'data' => $data
        ]);
    }
}
