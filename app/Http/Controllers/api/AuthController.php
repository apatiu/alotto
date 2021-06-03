<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PawnConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Rules\Password;
use Laravel\Jetstream\Jetstream;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $input = $request->all();
        $password_rule = new Password();
        $password_rule->length(6);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', $password_rule, 'confirmed'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ]);
        try {
            DB::beginTransaction();
            $token = '';
            $user = User::create([
                'name' => $input['name'],
                'username' => $input['username'],
                'password' => Hash::make($input['password']),
            ]);
            //                $this->createTeam($user);
            $token = $user->createToken('myapptoken')->plainTextToken;
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
        $user->load('currentTeam');
        return ['user' => $user, 'token' => $token];
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user &&
            Hash::check($request->password, $user->password)) {
            $user->load('currentTeam');
            return [
                'token' => $user->tokens()->whereName('myapptoken')->first()->token,
                'user' => $user
            ];
        }
        throw ValidationException::withMessages([
            Fortify::username() => [trans('auth.failed')],
        ]);
    }
}
