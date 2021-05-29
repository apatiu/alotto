<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\RedirectsActions;

class TeamController extends Controller
{
    use RedirectsActions;

    public function index() {
        return Jetstream::inertia()->render(request(),'Teams/Index',[
            'teams'=> request()->user()->ownedTeams
        ]);
    }
    /**
     * Show the team management screen.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $teamId
     * @return \Inertia\Response
     */
    public function show(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        Gate::authorize('view', $team);

        return Jetstream::inertia()->render($request, 'Teams/Show', [
            'team' => $team->load('owner', 'users', 'teamInvitations',
                'pawn_config','pawn_config.intr_range_rates', 'pawn_config.intr_discount_rates'),
            'availableRoles' => array_values(Jetstream::$roles),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);
    }

    /**
     * Show the team creation screen.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Jetstream::newTeamModel());

        return Inertia::render('Teams/Create');
    }

    /**
     * Create a new team.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $creator = app(CreatesTeams::class);

        $creator->create($request->user(), $request->all());

        return $this->redirectPath($creator);
    }

    /**
     * Update the given team's name.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $teamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);
        $user = $request->user();
        $input = $request->all();

        Gate::forUser($user)->authorize('update', $team);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateTeamName');

        if (isset($input['photo'])) {
            $team->updateProfilePhoto($input['photo']);
        }

        $team->forceFill([
            'name' => $input['name'],
            'company_name' => $input['company_name'],
            'addr' => $input['addr'],
            'branch_number' => $input['branch_number'],
            'tax_id' => $input['tax_id'],
            'phone' => $input['phone'],
        ])->save();
        return back(303);
    }

    /**
     * Delete the given team.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $teamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(ValidateTeamDeletion::class)->validate($request->user(), $team);

        $deleter = app(DeletesTeams::class);

        $deleter->delete($team);

        return $this->redirectPath($deleter);
    }

    public function destroyCurrentTeamProfilePhoto() {
        request()->user()->currentTeam->deleteProfilePhoto();
        return back(303)->with('status', 'profile-photo-deleted');
    }
}
