<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Rules\Role;

class AddTeamMember implements AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param mixed $user
     * @param mixed $team
     * @param string $email
     * @param string|null $role
     * @return void
     */
    public function add($user, $team, string $username, string $role = null)
    {

        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $username, $role,);

        $newTeamMember = $this->finduserByUsernameOrFail($username);

        AddingTeamMember::dispatch($team, $newTeamMember);

        $team->users()->attach(
            $newTeamMember, ['role' => $role]
        );

        TeamMemberAdded::dispatch($team, $newTeamMember);
    }

    public static function findUserByUsernameOrFail(string $username)
    {
        return Jetstream::newUserModel()->where('username', $username)->firstOrFail();
    }

    /**
     * Validate the add member operation.
     *
     * @param mixed $team
     * @param string $email
     * @param string|null $role
     * @return void
     */
    protected function validate($team, string $username, ?string $role)
    {
        Validator::make([
            'username' => $username,
            'role' => $role,
        ], $this->rules(), [
            'username.exists' => __('We were unable to find a registered user with this username.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $username)
        )->validateWithBag('addTeamMemberForm');
    }

    /**
     * Get the validation rules for adding a team member.
     *
     * @return array
     */
    protected function rules()
    {
        return array_filter([
            'username' => ['required', 'exists:users'],
            'role' => Jetstream::hasRoles()
                ? ['required', 'string', new Role]
                : null,
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     *
     * @param mixed $team
     * @param string $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnTeam($team, string $email)
    {
        return function ($validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
