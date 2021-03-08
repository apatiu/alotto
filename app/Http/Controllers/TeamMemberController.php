<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function store(Request $request, $teamId)
    {

        return back(303);
    }
}
