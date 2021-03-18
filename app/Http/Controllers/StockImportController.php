<?php

namespace App\Http\Controllers;

use App\Http\Helpers\GoldPriceHelper;
use App\Models\GoldPercent;
use App\Models\Product;
use App\Models\ProductDesign;
use App\Models\ProductType;
use App\Models\StockImport;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class StockImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('StockImports/Index', [
            'filters' => request()->all('search', 'role', 'trashed'),
            'items' => StockImport::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $product = null;
        if (request()->has('checkProduct')) {
            $data = request()->query();
            Validator::make($data, [
                'gold_percent' => ['required'],
                'product_type_id' => ['required'],
            ])->validateWithBag('lineBag');

            $product = new Product();
            $product->fill($data);
            $product->gen_product_id();
            $product->gen_product_name();

            $producted = Product::find($product->product_id);
            if ($producted) {
                $product = $producted;
            }
        }
        return Inertia::render('StockImports/Create', [
            'goldprice' => GoldPriceHelper::GoldPrice(),
            'filters' => request()->all('search', 'role', 'trashed'),
            'item' => [
                'd' => date('d-m-Y'),
                'rows' => []
            ],
            'suppliers' => Supplier::all(),
            'gold_percents' => GoldPercent::all(),
            'product_types' => ProductType::all(),
            'product_designs' => ProductDesign::all(),
            'searchproduct' => Inertia::lazy(function () use ($product) {
                return $product;
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['id'] = $this->get_id();
        $data['team_id'] = $request->user()->currentTeam->id;

        Validator::make($data, $this->validateRules())->validateWithBag('stockImportBag');

        DB::transaction(function () use ($data) {
            return tap(StockImport::create($data),
                function (StockImport $stockImport) use ($data) {
                    $stockImport->lines()->insert($data['lines']);
                });
        });

        return redirect()->back();
    }

    private function get_id()
    {
        $id = 'SI' . request()->user()->currentTeam->id . '-';
        $id .= date('Ym') . '-';

        $latest = StockImport::where('id', 'like', $id . '%')->select('id')->orderBy('id', 'desc')->first();

        if (!$latest)
            $id .= '0001';
        else
            $id .= substr('000' + (intval(substr($latest->id, -4)) + 1), -4);

        return $id;

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\StockImport $stockImport
     * @return \Illuminate\Http\Response
     */
    public function show(StockImport $stockImport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\StockImport $stockImport
     * @return \Illuminate\Http\Response
     */
    public function edit(StockImport $stockImport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\StockImport $stockImport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockImport $stockImport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\StockImport $stockImport
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockImport $stockImport)
    {
        //
    }

    public function validateRules()
    {
        return [
            'id' => ['required', 'unique:stock_imports'],
            'team_id' => ['required'],
            'dt' => ['required'],
            'real_cost' => ['required']
        ];
    }
}
