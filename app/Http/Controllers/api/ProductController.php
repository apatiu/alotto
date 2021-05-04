<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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
}
