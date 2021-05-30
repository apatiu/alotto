<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactRole;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

        Validator::make($request->all(), $this->validateRules())->validateWithBag('createCustomer');
        try {
            DB::beginTransaction();
            $cust = new Customer($request->all());
            $cust = Customer::create($request->all());
            DB::commit();
            return $cust;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception;
        }


    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer
        ]);
    }

    public function update(Request $request, $id)
    {

        Validator::make($request->all(), $this->validateRules())->validateWithBag('updateCustomer');

        DB::transaction(function () use ($request, $id) {
            $cust = Customer::find($id);
            $cust->update($request->all());
        });

        return redirect()->back();
    }

    public function validateRules()
    {
        return [
            'name' => ['required', 'string', 'max:255']
        ];
    }
}
