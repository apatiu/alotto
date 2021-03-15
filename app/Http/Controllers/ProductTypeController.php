<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('ProductTypes/Index', [
            'items' => ProductType::all()
        ]);
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
        Validator::make($request->all(), $this->validateRules())->validateWithBag('typeBag');;
        DB::transaction(function () use ($request) {
            ProductType::create($request->all());
        });
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductType $productType
     * @return \Illuminate\Http\Response
     */
    public function show(ProductType $productType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ProductType $productType
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductType $productType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductType $productType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductType $productType)
    {
        $rules = $this->validateRules();
        unset($rules['id']);
        Validator::make($request->all(), $rules)->validateWithBag('typeBag');;
        DB::transaction(function () use ($request, $productType) {
            $productType->name = $request->input('name');
            $productType->save();
        });
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductType $productType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductType $productType)
    {
        $productType->delete();
        return redirect()->back();
    }

    private function validateRules()
    {
        return [
            'id' => ['required', 'unique:product_types'],
            'name' => ['required']
        ];
    }
}
