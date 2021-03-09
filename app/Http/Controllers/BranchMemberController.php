<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;

class BranchMemberController extends Controller
{
    public function store(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        if (Features::sendsTeamInvitations()) {
            app(InvitesTeamMembers::class)->invite(
                $request->user(),
                $team,
                $request->email ?: '',
                $request->role
            );
        } else {
            app(AddsTeamMembers::class)->add(
                $request->user(),
                $team,
                $request->username ?: '',
                $request->role
            );
        }

        return back(303);
    }
}
