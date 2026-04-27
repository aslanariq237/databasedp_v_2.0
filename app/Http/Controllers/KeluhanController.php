<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;

class KeluhanController extends Controller
{
    public function index()
    {
        $keluhans = Keluhan::latest()->paginate(10);

        return view('container.master.keluhan.index', compact('keluhans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:keluhan,name',
            'price' => 'required|numeric|min:0',
        ]);

        Keluhan::create($validated);

        return redirect()->route('keluhan.index')
            ->with('success', 'Jenis keluhan berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $keluhan = Keluhan::findOrFail($id);

        // Optional: cek apakah keluhan ini sudah dipakai di detail
        if ($keluhan->details()->exists()) { // jika ada relasi hasMany KeluhanDetail
            return redirect()->back()->with('error', 'Tidak bisa hapus karena keluhan ini sudah dipakai di data detail.');
        }

        $keluhan->delete();

        return redirect()->route('keluhan.index')
            ->with('success', 'Jenis keluhan berhasil dihapus!');
    }
}
