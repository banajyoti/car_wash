<?php

namespace App\Http\Controllers;

use App\Models\SagmentType;
use App\Models\WashType;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('user_type', 2)
                     ->withCount('washes')
                     ->get();
        return view('admin', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'required|string|max:20',
            'car_no' => 'required|string|max:20',
            'password'=> 'required',
        ]);

        User::create([
            'name' => $request->customerName,
            'phone' => $request->customerPhone,
            'car_no' => $request->car_no,
            'user_type' => 2,
            'password' => Hash::make(123456),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Customer added successfully.');
    }

    public function edit(User $customer)
    {
        return view('edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $request->validate([
            'customerName' => 'required',
            'customerPhone' => 'required',
            'car_no' => 'required',
        ]);

        $customer->update([
            'name' => $request->customerName,
            'phone' => $request->customerPhone,
            'car_no' => $request->car_no,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Customer updated successfully.');
    }

    public function destroy(User $customer)
    {
        $customer->loadCount('washes');

        if ($customer->washes_count >= 1) {
            return redirect()->route('admin.dashboard')->with('error', 'Cannot delete customer with more than 1 wash.');
        }
        $customer->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Customer deleted successfully.');
    }

    public function getPlan(Request $request) 
    {
        $segments = SagmentType::all();
        $washtypes = WashType::all();
        $sagments = Plan::leftJoin('sagment_types', 'sagment_types.id', '=', 'sagment_prices.sagment_id')
            ->select('sagment_types.name as sg', 'sagment_prices.*')
            ->get();
        return view('getPlan', compact('segments', 'washtypes', 'sagments'));
    }

    public function getprocessing(Request $request)
    {
        $segments = SagmentType::all();
        $washtypes = WashType::all();
        $sagment_prices = Plan::all();

        $customers = User::where('user_type', 2)->withCount('washes')->get();
        return view('processing', compact('segments', 'washtypes', 'sagment_prices', 'customers'));
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

    return redirect()->back()->with([
        'success' => 'Submitted successfully.',
        'price'   => $price,
    ]);

    //return redirect()->back()->with('success', 'Submitted successfully.');
}

public function storePlan(Request $request)
{   
    $request->validate([
        'sagment_id' => 'required',
        'name' => 'required|string',
        'prices' => 'required|numeric|min:0',
    ]);
    
    DB::table('sagment_prices')->insert([
        'prices' => $request['prices'],
        'sagment_id' => $request['sagment_id'],
        'name' => $request['name'],
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->back()->with('success', 'Plan added successfully.');
}

// app/Http/Controllers/PlanController.php

public function updatePlan(Request $request, $id)
{
    $request->validate([
        'sg' => 'required|string',
        'name' => 'required|string',
        'prices' => 'required|numeric|min:0',
    ]);

    $plan = Plan::findOrFail($id);
    $plan->update([
        'sg' => $request->sg,
        'name' => $request->name,
        'prices' => $request->prices,
    ]);

    return redirect()->back()->with('success', 'Plan updated successfully.');
}

public function getBySagment($id)
{
    $data = Plan::where('sagment_id', $id)->get();

    return response()->json($data);
}


}