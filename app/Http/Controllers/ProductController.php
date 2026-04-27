<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::latest()->paginate(10);

        return view('container.master.product.index', compact('product'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'  => 'required|string|max:255',
            'code'  => 'required',
            'serial'=> 'required',
        ]);

        Product::create($validate);
        return redirect()
            ->route('product.index')
            ->with('success', 'Product berhasil di tambahkan');
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
        $product = Product::findOrFail($id);

        if (!$product->exists()) {
            return redirect()
                ->back()
                ->with('error', 'tidak dapat menemukan product');
        }

        return redirect()
            ->route('product.index')
            ->with('success', 'Product berhasil Di Hapus');
    }
}
