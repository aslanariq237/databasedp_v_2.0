<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomersImport;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query()            
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Cari di header receive
                $q->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");                
            });            
        }

        $customers = $query        
        ->paginate(10)
        ->appends($request->query());

        return view('container.master.customer.index', compact('customers'));
    }

    public function create(){
        return view('container.master.customer.form', ['customer' => new Customer()]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $import = new CustomersImport();
            Excel::import($import, $request->file('file'));

            $message = "Import selesai! ";
            $message .= "Berhasil insert: {$import->inserted} data. ";
            $message .= "Skipped: {$import->skipped} data (duplikat code).";

            // Tampilkan detail debug di session (sementara untuk test)
            return redirect()->route('customer.index')
                ->with('success', $message)
                ->with('import_details', $import->details); // kirim ke view untuk lihat log

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function edit(Customer $customer)
    {
        return view ('container.master.customer.form', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'company'  => 'nullable|string|max:255',
            'category' => 'required|integer|between:1,4',
            'pic'      => 'nullable|integer',
            'telp'     => 'required|string|max:20',
            'alamat'   => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('container.master.customer.form');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
