<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 0;
        $barang = BarangKeluar::latest()->paginate(5);
        return view('barangkeluar.index', compact('barang', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::all();
        return view('barangkeluar.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'qty_keluar' => 'required|numeric|min:1',
            'barang_id' => 'required|exists:barang,id',
            'tgl_keluar' => 'required|date',
        ]);
        $tgl_keluar = $request->tgl_keluar;
        $barang_id = $request->barang_id;
    

        $invalidDate = BarangMasuk::where('barang_id', $barang_id)
            ->where('tgl_masuk', '>', $tgl_keluar)
            ->exists();
    
        if ($invalidDate) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal invalid']);
        }
    
        $barang = Barang::find($barang_id);
    
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah melebihi stok!']);
        }
        BarangKeluar::create($request->all());
        return redirect()->route('barangkeluar.index')->with(['success' => 'Record keluar berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = BarangKeluar::findOrFail($id);
        return view('barangkeluar', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $barang = Barang::all();
        return view('barangkeluar.edit', compact('barangKeluar', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'qty_keluar' => 'required|numeric|min:1',
            'barang_id' => 'required|exists:barang,id',
            'tgl_keluar' => 'required|date',
        ]);
        $tgl_keluar = $request->tgl_keluar;
        $barang_id = $request->barang_id;
        $barangKeluar = BarangKeluar::findOrFail($id);

        $invalidDate = BarangMasuk::where('barang_id', $barang_id)
            ->where('tgl_masuk', '>', $tgl_keluar)
            ->exists();
    
        if ($invalidDate) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal invalid']);
        }
    
        $barang = Barang::find($barang_id);
    
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah melebihi stok!']);
        }
        $barangKeluar->update($request->all());
        return redirect()->route('barangkeluar.index')->with(['success' => 'Record telah diubah']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = BarangKeluar::findOrFail($id);
        $barang->delete();
        return redirect()->route('barangkeluar.index')->with(['success' => 'Record keluar berhasil dihapus']);
    }
}
