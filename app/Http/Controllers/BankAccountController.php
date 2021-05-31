<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('BankAccounts/Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            BankAccount::create($request->all());
        });
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\BankAccount $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\BankAccount $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankAccount $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        DB::transaction(function () use ($request, $bankAccount) {
            $bankAccount->update($request->all());
        });
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\BankAccount $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return redirect()->back();
    }
}
