<?php

namespace App\Http\Controllers;

use App\Models\GoldPercent;
use App\Models\Product;
use App\Models\ProductDesign;
use App\Models\ProductType;
use App\Models\StockCard;
use App\Models\StockImport;
use App\Models\StockImportLine;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Carbon\Carbon;

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
            'items' => StockImport::with('team')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('StockImports/FormStockImport', [
                'item' => [
                    'id' => null,
                    'dt' => now(),
                    'team_id' => null,
                    'supplier_id' => null,
                    'emp_name' => null,
                    'status' => 'draft',
                    'stock_updated_on' => null,
                    'note' => null,
                    'product_weight_total' => null,
                    'tag_wage_total' => null,
                    'tag_price_total' => null,
                    'product_qty_total' => null,
                    'cost_wage_total' => null,
                    'cost_price_total' => null,
                    'cost_gold_total' => null,
                    'real_weight_total' => null,
                    'real_cost' => null,
                    'lines' => [],
                ],
                'suppliers' => Supplier::all(),
                'gold_percents' => GoldPercent::all(),
                'product_types' => ProductType::all(),
                'product_designs' => ProductDesign::all(),
                'searchproduct' => Inertia::lazy(function () {
                    return $this->checkProduct();
                }),
            ]
        );
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
        $data['id'] = $this->gen_id();
        $data['team_id'] = $request->user()->currentTeam->id;
        $data['status'] = 'draft';
        $data['dt'] = Carbon::create($data['dt'])->toDateTimeString();

        Validator::make($data, $this->validateRules())->validateWithBag('stockImportBag');

        DB::transaction(function () use ($request, $data) {
            tap(StockImport::create($data), function (StockImport $stockImport) use ($request) {
                $stockImport->lines()->createMany($request->input('lines', []));
            });

        });

        return redirect()->route('stock-imports.edit', $data['id']);
    }

    private function gen_id()
    {
        $id = 'SI' . request()->user()->currentTeam->id . '-';
        $id .= date('Ym') . '-';

        $latest = StockImport::where('id', 'like', $id . '%')->select('id')->orderBy('id', 'desc')->first();
        if (!$latest)
            $id .= '0001';
        else
            $id .= substr('000' . (intval(substr($latest->id, -4)) + 1), -4);

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
     * @return \Inertia\Response
     */
    public function edit(StockImport $stockImport)
    {
        $stockImport->load('lines');
        return Inertia::render('StockImports/FormStockImport', [
            'item' => $stockImport,
            'suppliers' => Supplier::all(),
            'gold_percents' => GoldPercent::all(),
            'product_types' => ProductType::all(),
            'product_designs' => ProductDesign::all(),
            'searchproduct' => Inertia::lazy(function () {
                return $this->checkProduct();
            }),
        ]);
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

        Validator::make($request->all(), $this->validateRules())
            ->validateWithBag('stockImportBag');

        $data = $request->all();
        DB::transaction(function () use ($data, $stockImport) {
            $data['dt'] = Carbon::create($data['dt'])->toDateTimeString();
            $approve_request = false;

            $stockImport->fill($data)->save();
            $stockImport->lines()->delete();
            $stockImport->lines()->createMany($data['lines']);

            if ($data['status'] == 'approve-request') {

                $stockImport->refresh();
                foreach ($stockImport->lines as $line) {
                    $this->line_product_processing($line);
                }
                $stockImport->status = 'approved';
                $stockImport->save();
                $this->updateStockCard($stockImport);
            };
        });

        return redirect()->back();
    }

    public function line_product_processing(StockImportLine $line)
    {
        $product = Product::whereProductId($line->product_id)->first();
        if (!$product) {
            $product = new Product();
            $product->fill([
                'product_id' => $line->product_id,
                'name' => $line->product_name,
                'team_id' => $line->stock_import->team_id,
                'gold_percent' => $line->gold_percent,
                'product_type_id' => $line->product_type_id,
                'product_design_id' => $line->product_design_id,
                'size' => $line->product_size,
                'min_qty' => $line->product_min,
                'weight' => $line->product_weight,
                'weightbaht' => $line->product_weightbaht,
                'cost_wage' => $line->cost_wage,
                'cost_price' => $line->cost_price,
                'tag_wage' => $line->tag_wage,
                'tag_price' => $line->tag_price,
                'avg_cost_per_baht' => $line->avg_cost_per_baht,
                'sale_with_gold_price' => $line->sale_with_gold_price,
                'wage_by_pcs' => $line->wage_by_pcs,
                'qty' => $line->product_qty,
                'description' => $line->description,
            ]);
            $product->save();
        } else {
            $product_weight = $product->qty * weightgram($product->weight, $product->weightbaht);
            $product_cost_per_gram = $product->avg_cost_per_baht / 15.2;
            $line_weight = $line->qty * weightgram($line->weight, $line->weightbaht);
            $line_cost_per_gram = $line->avg_cost_per_baht / 15.2;
            $old_qty = $product->qty;
            $new_qty = $old_qty + $line->product_qty;

            $product->avg_cost_per_baht = ($new_qty === 0) ? 0 :
                (
                    (
                        ($product_weight * $product_cost_per_gram) +
                        ($line_weight * $line_cost_per_gram)
                    ) / $new_qty
                ) * 15.2;
            $product->qty = $new_qty;
            $product->cost_wage =
                (
                    ($old_qty * $product->cost_wage) + ($line->qty * $line->cost_wage)
                ) / $new_qty;
            $product->cost_price =
                (
                    ($old_qty * $product->cost_price) + ($line->qty * $line->cost_price)
                ) / $new_qty;

        }

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
            'id' => ['required'],
            'team_id' => ['required'],
            'dt' => ['required'],
            'real_cost' => ['required']
        ];
    }

    public function checkProduct()
    {
        $product = null;
        if (request()->input('checkProduct', false)) {

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
        return $product;
    }

    private function updateStockCard(StockImport $bill)
    {
        foreach ($bill->lines as $line) {
            $data = [
                'product_id' => $line->product_id,
                'cost_wage' => $line->cost_wage,
                'tag_wage' => $line->tag_wage,
                'cost_price' => $line->cost_price,
                'tag_price' => $line->tag_price,
                'qty_begin' => 0,
                'qty_in' => $line->product_qty,
                'qty_out' => 0,
                'qty_end' => $line->product_qty,
                'weight_begin' => 0,
                'weight_in' => weightgram($line->product_weight, $line->product_weightbaht),
                'weight_out' => 0,
                'weight_end' => weightgram($line->product_weight, $line->product_weightbaht),
                'description' => $line->description,
                'dt' => $bill->dt,
                'ref_no' => $bill->id,
                'user_id' => Auth::user()->id
            ];
            $last = StockCard::whereProductId($line->product_id)->latest('dt')->first();
            if ($last) {
                $data['qty_begin'] = $last->qty_end;
                $data['weight_begin'] = $last->qty_end;
            }
            $data['qty_end'] += ($data['qty_in'] - $data['qty_out']);
            $data['weight_end'] += ($data['weight_in'] - $data['weight_out']);

            StockCard::create($data);
        }
    }
}
