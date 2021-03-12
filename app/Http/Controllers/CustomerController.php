<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index() {
        return Inertia::render('Customers/Index',[
            'filters' => request()->all('search', 'role', 'trashed'),
            'items' => Contact::whereHas('roles',function(Builder $query) {
                $query->where('name','customer');
            })->get()
        ]);
    }
}
