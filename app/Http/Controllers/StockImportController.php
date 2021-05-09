<?php

namespace App\Http\Controllers;

use App\Models\GoldPercent;
use App\Models\Product;
use App\Models\ProductDesign;
use App\Models\ProductType;
use App\Models\Shift;
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
    private $bill;

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
                    'code' => null,
                    'dt' => now(),
                    'team_id' => request()->user()->currentTeam->id,
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
        $data['code'] = $this->genCode();
        $data['team_id'] = $request->user()->currentTeam->id;
        $data['status'] = 'draft';
        $data['dt'] = Carbon::create($data['dt'])->toDateTimeString();

        Validator::make($data, $this->validateRules())
            ->validateWithBag('stockImportBag');

        $this->bill = new StockImport();

        DB::transaction(function () use ($request, $data) {
            $this->bill->fill($data);
            $this->bill->save();
            $this->bill->lines()->createMany($request->input('lines', []));

            if ($request->input('status') == 'approve-request') {
                $this->approveBill();
            }
        });
        return redirect()->route('stock-imports.edit', $this->bill->id);
    }

    private function genCode()
    {
        $code = 'SI' . request()->user()->currentTeam->id . '-';
        $code .= date('Ym') . '-';

        $latest = StockImport::where('code', 'like', $code . '%')
            ->latest()->first();
        if (!$latest)
            $code .= '0001';
        else
            $code .= substr('000' . (intval(substr($latest->code, -4)) + 1), -4);

        return $code;

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
     * @param \App\Models\StockImport $this- >bill
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, StockImport $stockImport)
    {
        $this->bill = $stockImport;

        Validator::make($request->all(), $this->validateRules())
            ->validateWithBag('stockImportBag');

        $data = $request->all();
        $data['dt'] = jsDateToDateString($data['dt']);

        DB::transaction(function () use ($data) {

            $this->bill->fill($data)->save();
            $this->bill->lines()->delete();
            $this->bill->lines()->createMany($data['lines']);

            if ($data['status'] == 'approve-request') {
                $this->approveBill();
            };
        });

        $this->bill->refresh();
        return redirect()->back();
    }

    public function approveBill()
    {
        $this->bill->refresh();
        foreach ($this->bill->lines as $line) {
            $this->line_product_processing($line, true);
        }
        $this->bill->status = 'approved';
        $this->bill->save();

        $this->bill->payments()->create([
            'team_id' => $this->bill->team_id,
            'shift_id' => Shift::current()->id,
            'payment_type_id' => 'stock-import',
            'payment_no' => '',
            'dt' => $this->bill->dt,
            'detail' => 'นำเข้าสินค้า',
            'pay' => $this->bill->real_cost,
            'method' => 'cash',
        ]);
    }

    public function line_product_processing(StockImportLine $line, $updateStock = false)
    {
        $found = Product::whereCode($line->product_code)->exists();
        if (!$found) {
            $product = new Product();
            $product->fill([
                'code' => $line->product_code,
                'name' => $line->product_name,
                'team_id' => request()->user()->currentTeam->id,
                'gold_percent_id' => $line->gold_percent_id,
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
                'qty' => $line->qty,
                'description' => $line->description,
            ]);
            $product->save();
        } else {
            $product = Product::whereProductId($line->product_code)->first();
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
            $product->save();
        }

        if ($updateStock) {
            $this->updateStockCard($product, $line);
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
            'code' => ['required'],
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
            $product->getCode();
            $product->genName();

            $producted = Product::find($product->product_code);
            if ($producted) {
                $product = $producted;
            }
        }
        return $product;
    }

    private function updateStockCard(Product $product, $line)
    {

        $sc = StockCard::where([
            ['team_id', '=', $product->team_id],
            ['product_id', '=', $product->id]
        ])
            ->orderBy('dt', 'desc')
            ->first();

        if ($sc === null) {
            $sc = new StockCard([
                'team_id' => $product->team_id,
                'product_id' => $product->id,
                'cost_wage' => $line->cost_wage,
                'tag_wage' => $line->tag_wage,
                'cost_price' => $line->cost_price,
                'tag_price' => $line->tag_price,
                'qty_begin' => 0,
                'qty_in' => 0,
                'qty_out' => 0,
                'qty_end' => 0,
                'weight_begin' => 0,
                'weight_in' => 0,
                'weight_out' => 0,
                'weight_end' => 0,
                'description' => 'นำเข้า',
                'dt' => null,
                'user_id' => Auth::user()->id
            ]);
            $sc->save();
        }

        $new = $sc->replicate();
        $new->qty_begin = $new->qty_end;
        $new->qty_in = $line->qty;
        $new->qty_end = $new->qty_begin + $new->qty_in;
        $new->weight_begin = $new->weight_out;
        $new->weight_in = $line->product_weight_total;
        $new->weight_end = $new->weight_begin + $new->weight_in;
        $new->dt = $this->bill->dt;
        $new->ref_id = $this->bill->id;
        $new->ref_type = StockImport::class;
        $product->stockCards()->save($new);

    }
}
