<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 0;
        $barang = Barang::latest()->paginate(5);
        return view('barang.index', compact('barang', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $arrayKategori = Kategori::all();
        return view('barang.create', compact('arrayKategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'merk'          => 'required',
            'seri'          => 'required',
            'spesifikasi'   => 'required',
            'stok'          => 'required',
            'kategori_id'   => 'required',
        ]);

        Barang::create($request->all());
        return redirect()->route('barang.index')->with(['success'=>'Barang Tersimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        $barang = Barang::findOrFail($barang->id);
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $akategori = Kategori::all();
        $barang = Barang::findOrFail($barang->id);
        $selectedKategori = Kategori::find($barang->kategori_id);
        return view('barang.edit', compact('barang', 'akategori', 'selectedKategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'merk'        => 'required',
            'seri'        => 'required',
            'spesifikasi' => 'required',
            'stok'        => 'required',
            'kategori_id' => 'required',
        ]);
        $barang = Barang::findOrFail($barang->id);
        $barang->update($request->all());
        return redirect()->route('barang.index')->with(['success'=>'Berhasil Menyimpan Barang!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        $barang = Barang::findOrFail($barang->id);
        if(DB::table('barangmasuk')->where('barang_id', $barang->id)->exists()){
            return redirect()->route('barang.index')->with(['error' => 'Barang masih terpakai']);
        }else{
            $barang->delete();
            return redirect()->route('barang.index')->with(['success'=>'Barang Dihapus']);
        }
    }
}
