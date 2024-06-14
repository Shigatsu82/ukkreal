<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 0;
        $records = BarangMasuk::latest()->paginate(5);
        return view('barangmasuk.index', compact('records', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::all();
        return view('barangmasuk.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_masuk' => 'required',
            'qty_masuk' => 'required|numeric|min:0',
            'barang_id' => 'required'
        ],[
            'qty_masuk.min' => 'Kuantitas tidak valid' 
        ]);

        BarangMasuk::create($request->all());
        return redirect()->route('barangmasuk.index')->with(['success'=>'Record Tersimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = BarangMasuk::findOrFail($id);
        return view('barangmasuk.show', compact('record')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = BarangMasuk::find($id);
        return view('barangmasuk.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'qty_masuk' => 'required|numeric|min:0',
            'tgl_masuk' => 'required',
            'barang_id' => 'required'
        ],[
            'qty_masuk.min' => 'Kuantitas tidak valid',
            'qty_masuk.numeric' => 'Nilai haruslah angka'
        ]);
        $barang = BarangMasuk::findOrFail($id);
        $barang->update();
        return redirect()->route('barangmasuk.index')->with(['success' => 'Record Masuk telah diganti']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = BarangMasuk::findOrFail($id);
        $barang->delete();
        return redirect()->route('barangmasuk.index')->with(['success' => 'Record masuk telah dihapus']);
    }
}
