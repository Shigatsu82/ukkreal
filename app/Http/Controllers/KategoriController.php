<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('kategori')->select('id', 'deskripsi', DB::raw("ketKategorik(kategori) as info"))->orderBy('kategori', 'asc');
        $search = $request->keyword;
        if(!empty($search)){
            $query->where('deskripsi', 'LIKE', "%$search%")
            ->orWhere('ketKategorik(kategori)', 'LIKE', ["%$search%"]);
        }
        $i = 0;
        $rsetKategori = $query->paginate(5);
        return view('kategori.index', compact('rsetKategori', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $arrayKategori = array(
            'blank' => 'Pilih Kategori',
            'A' => 'Alat',
            'M' => 'Modal',
            'BHP' => 'Barang Habis Pakai',
            'BTHP' => 'Barang Tidak Habis Pakai'
        );
        return view('kategori.create', compact('arrayKategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'deskripsi' => 'required|min:5',
            'kategori' => 'required|in:M,A,BHP,BTHP'
        ]);
        try{
            DB::beginTransaction();
            Kategori::create([
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                'status' => 'pending'
            ]);
            DB::commit();
            Session::flash('success', 'Kategori berhasil disimpan');
            }catch(\Exception $e){
                DB::rollback();
                report($e);
                Session::flash('error', 'Kategori tidak tersimpan');
            }
            
        return redirect()->route('kategori.index')->with(['success'=>'Kategori tersimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        $kategori = Kategori::findOrFail($kategori->id);
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        $rsetKategori = Kategori::findOrFail($kategori->id);
        return view('kategori.edit', compact('rsetKategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validate = $request->validate([
            'kategori' => 'required|in:A,M,BHP,BTHP',
            'deskripsi' => 'required|min:5',
        ]);
        $kategori = Kategori::findOrFail($kategori->id);
        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with(['success'=>'Berhasil Mengubah Data']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        if(DB::table('barang')->where('kategori_id', $kategori->id)->exists()){
            return redirect()->route('kategori.index')->with(['error'=>'Kategori ini masih terpakai']);
        }else{
        $kategori = Kategori::findOrFail($kategori->id);
        $kategori->delete();
        return redirect()->route('kategori.index')->with(['success'=>'Kategori dihapus']);
       }
    }
}
