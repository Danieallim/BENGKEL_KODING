<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat; // Pastikan menggunakan model Obat
use Illuminate\Http\Request;

class ObatController extends Controller // Nama class harus ObatController
{
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil di Tambah')
            ->with('type', 'success');
    }

    public function edit(Obat $obat)
    {
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'tambah_stok' => 'nullable|integer|min:0',
            'kurangi_stok' => 'nullable|integer|min:0',
        ]);

        $tambahStok = (int) ($request->tambah_stok ?? 0);
        $kurangiStok = (int) ($request->kurangi_stok ?? 0);
        $stokBaru = ((int) $obat->stok) + $tambahStok - $kurangiStok;

        if ($stokBaru < 0) {
            return back()
                ->withInput()
                ->with('error', 'Pengurangan stok melebihi stok yang tersedia.');
        }

        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
            'stok' => $stokBaru,
        ]);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil diupdate.');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();
        return redirect()->route('obat.index')
            ->with('message', 'Data Obat Berhasil Di Hapus')
            ->with('type', 'success');
    }
}