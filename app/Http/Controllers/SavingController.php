<?php

namespace App\Http\Controllers;

use App\Models\Pawn;
use App\Models\Saving;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SavingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Saving::with(['customer', 'team']);
        $filters = [];

        if (request()->has('filters')) {
            $filters = request('filters');
        } else {
            $filters = [
                'status' => 'open'
            ];
        }

        if ($filters['dt_due_over'] ?? 0 > 0) {
            $data->where(
                'dt_due',
                '<=',
                now()->addDays(0 - $filters['dt_due_over'])->toDateString())
                ->whereNotNull('dt_due');
        }

        if (isset($filters['q'])) {
            $data->where(function ($q) use ($filters) {
                $q->where('id', 'like', "%{$filters['q']}%");
                $q->orWhereHas('customer', function ($query) use ($filters) {
                    return $query->where('name', 'like', "%{$filters['q']}%");
                });
            });
        }

        if ($filters['status']) {
            $data->whereIn('status', explode(',', $filters['status']));
        }


        $pagination = request('pagination', [
            'rowsPerPage' => 12
        ]);

        return Inertia::render('Savings/Index', [
            'filters' => $filters,
            'pagination' => $pagination,
            'data' => $data->paginate($pagination['rowsPerPage']),
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Saving $saving
     * @return \Illuminate\Http\Response
     */
    public function show(Saving $saving)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Saving $saving
     * @return \Illuminate\Http\Response
     */
    public function edit(Saving $saving)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Saving $saving
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Saving $saving)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Saving $saving
     * @return \Illuminate\Http\Response
     */
    public function destroy(Saving $saving)
    {
        //
    }
}
