<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactRole;
use App\Models\Customer;
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
    public function index()
    {
        return Inertia::render('Customers/Index', [
            'filters' => request()->all('search', 'role', 'trashed'),
            'items' => Customer::all(),
            'editingItem' => null
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createCustomer');

        DB::transaction(function () use ($data) {
            return tap(Customer::create([
                'name' => $data['name'],
            ]), function (Customer $customer) {

            });
        });

        return redirect()->back();

    }

    public function show($id) {
        return Customer::find($id);
    }
}
