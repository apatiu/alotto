<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => request()->all('search', 'role', 'trashed'),
            'users' => request()->user()->staffs()
        ]);
    }

    public function create(){
        return Inertia::render('Users/Create',[
            'teams' => Team::all()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'team_id' => ['required'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validateWithBag('createUser');

        DB::transaction(function () use ($data) {
            return tap(User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),

            ]), function (User $user) use ($data) {
                $user->teams()->attach($data['team_id'],['role' => 'officer']);
            });
        });
//        return DB::transaction(function () use ($input) {
//            return tap(User::create([
//                'name' => $input['name'],
//                'username' => $input['username'],
//                'password' => Hash::make($input['password']),
//            ]), function (User $user) {
//                $this->createTeam($user);
//            });
//        });

        return redirect()->back();

    }
}
