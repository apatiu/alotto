<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class CustomerController extends Controller
{
    public function index() {
        return Inertia::render('Customers/Index',[
            'filters' => request()->all('search', 'role', 'trashed'),
            'items' => Contact::whereHas('roles',function(Builder $query) {
                $query->where('name','customer');
            })->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createCust');

        DB::transaction(function () use ($data) {
            return tap(User::create([
                'name' => $data['name'],
            ]), function (User $user) {

            });
        });

        return redirect()->back();

    }

}
