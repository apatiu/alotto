<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SavingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavingDetailController extends Controller
{
    public function destroy($id)
    {
        DB::transaction(function() use ($id) {
            $sd = SavingDetail::find($id);
            $sd->payments()->detach();
            $sd->payments()->delete();
            $saving = $sd->savingModel;
            $sd->delete();
            $saving->updateTotal();
            $saving->save();
        });

        return true;
    }
}
