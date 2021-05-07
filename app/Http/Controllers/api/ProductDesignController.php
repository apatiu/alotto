<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductDesign;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProductDesignController extends Controller
{
    private $page_title = 'ลายสินค้า';
    private $model = ProductDesign::class;
    private $bag = 'designBag';

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductDesign::all();
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
            ->validateWithBag($this->bag);;

        DB::transaction(function () use ($request) {
            $this->model::create($request->all());
        });
        return $this->model;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductDesign $productDesign
     * @return \Illuminate\Http\Response
     */
    public function show(ProductDesign $productDesign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ProductDesign $productDesign
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductDesign $productDesign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductDesign $productDesign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductDesign $productDesign)
    {
        $rules = $this->validateRules();
        unset($rules['id']);
        Validator::make($request->all(), $rules)->validateWithBag($this->bag);;
        DB::transaction(function () use ($request) {
            $this->model::find($request->input('id'))->fill($request->all())->save();
        });
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductDesign $productDesign
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->model::destroy($id);
        return redirect()->back();
    }

    private function validateRules()
    {
        return [
            'product_type_id'=> ['required'],
            'name' => ['required']
        ];
    }
    public function filterType(ProductType $productType) {
        return $productType->designs;
    }
}
