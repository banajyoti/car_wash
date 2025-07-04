<?php

namespace App\Http\Controllers;

use App\Models\WashType;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use App\Models\SagmentType;
use Illuminate\Support\Facades\Hash;

class SalesProcessingController extends Controller
{
     public function getprocessing(Request $request)
    {
        $segments = SagmentType::all();
        $washtypes = WashType::all();
        $sagment_prices = Plan::all();

        $customers = User::where('user_type', 2)->withCount('washes')->get();
        return view('customer.processing', compact('segments', 'washtypes', 'sagment_prices', 'customers'));
    }

    public function searchBasicPlan(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('user_type', 2)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%")
                        ->orWhere('car_no', 'like', "%{$query}%");
                    })
                    ->withCount('washes')
                    ->get();

    return response()->json($users);
    }
    public function storePremiumSelection(Request $request)
    { 
    $validated = $request->validate([
        'customer_id' => 'required|exists:users,id',
        'sagment_id' => 'required',
        'wash_type_id' => 'required',
        'prices' => 'required|numeric',
    ]);

    $customer = User::withCount('washes')->find($request->customer_id);
    $price = $customer->washes_count > 4 ? null : $request->prices;

    DB::table('plan_configurs')->insert([
        'cutomer_id' => $request['customer_id'],
        'sagment_id' => $request['sagment_id'],
        'wash_type_id' => $request->wash_type_name,
        'prices' => $price,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->back()->with('success', 'submitted successfully.');
}
}
