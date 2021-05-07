<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $rows = Product::orderBy('product_id');

        $q = $request->input('q', '');
        if (!empty($q)) {
            $rows->where('product_id', 'like', $q . '%');
        } else {
            return [];
        }

        return $rows->get();
    }

    public function check(Request $request)
    {

        $product = null;

        $data = request()->all();
        Validator::make($data, [
            'gold_percent_id' => ['required'],
            'product_type_id' => ['required'],
        ])->validateWithBag('lineBag');

        $product = new Product();
        $product->fill($data);
        $product->getCode();
        $product->genName();

        $producted = Product::find($product->code);
        if ($producted) {
            $product = $producted;
        }

        return $product;

    }
}
