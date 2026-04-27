<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teknisi;

class TeknisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teknisi = Teknisi::latest()->paginate(10);

        return view('container.master.teknisi.index', compact('teknisi'));
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'status'    => 'required'
        ]);

        Teknisi::create($validate);

        return redirect()->route('teknisi.index')
            ->with('success', 'Teknisi Berhasil ditambahkan');
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
        $teknisi = Teknisi::findOrFail($id);

        if(!$teknisi->exists()){
            return redirect()
                ->back()
                ->with('error', 'tidak dapat menemukan teknisi');            
        }

        return redirect()
            ->route('teknisi.index')
            ->with('success', 'Teknisi berhasil di hapus');
    }
}
