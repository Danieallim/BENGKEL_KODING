<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $obatIds = json_decode($request->obat_json, true);

        // if(!is_array($obatIds) || count($obatIds) === 0){
        //     return back()->with('error', 'Obat wajib diisi bos')->withInput();

        $obatCounts = array_count_values($obatIds);
        $obats = Obat::whereIn('id', array_keys($obatCounts))->get()->keyBy('id');

        foreach ($obatCounts as $idObat => $jumlah) {
            $obat = $obats->get($idObat);

            if (!$obat) {
                return back()->with('error', 'Obat tidak ditemukan.')->withInput();
            }

            if ($obat->stok < $jumlah) {
                return back()
                    ->with('error', 'Stok obat ' . $obat->nama_obat . ' tidak mencukupi atau sudah habis.')
                    ->withInput();
            }
        }

        DB::transaction(function () use ($request, $obatIds, $obatCounts) {
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $request->biaya_periksa + 150000,
            ]);

            foreach ($obatIds as $idObat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $idObat,
                ]);
            }

            foreach ($obatCounts as $idObat => $jumlah) {
                $obat = Obat::find($idObat);
                $obat->decrement('stok', $jumlah);
            }
        });

        return redirect()->route('periksa-pasien.index')->with('success', 'Data periksa berhasil disimpan.');
    }
}