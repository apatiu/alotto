<?php

namespace App\Http\Controllers;

use App\Models\ProductSize;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProductSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('ProductSizes/Index', [
            'items' => ProductSize::all()
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
        Validator::make($request->all(), $this->validateRules())
            ->validateWithBag('productSizeBag');
        DB::transaction(function () use ($request) {
            ProductSize::create($request->all());
        });
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductSize $productSize
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSize $productSize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ProductSize $productSize
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSize $productSize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductSize $productSize
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSize $productSize)
    {
        $rules = $this->validateRules();
        unset($rules['id']);
        Validator::make($request->all(), $rules)->validateWithBag('productSizeBag');;
        DB::transaction(function () use ($request, $productSize) {
            $productSize->name = $request->input('name');
            $productSize->save();
        });
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductSize $productSize
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSize $productSize)
    {
        $productSize->delete();
        return redirect()->back();
    }

    private function validateRules()
    {
        return [
            'name' => ['required','unique:product_sizes']
        ];
    }
}
