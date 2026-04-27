<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Receive;

class HelperController extends Controller
{
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->limit(20)
            ->get(['id', 'name as text', 'code']); // 'text' untuk kompatibilitas JS

        return response()->json($products);
    }

    public function searchCustomers(Request $request)
    {
        $query = $request->get('q', '');
        if (strlen($query) < 2) return response()->json([]);

        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->limit(15)
            ->get(['id', 'name as text', 'code']);

        return response()->json($customers);
    }

    public function getReceiveDetails(Receive $receive)
    {        
        return response()->json([
            'receive' => $receive,
            'details' => $receive->details
        ]);
    }
}
