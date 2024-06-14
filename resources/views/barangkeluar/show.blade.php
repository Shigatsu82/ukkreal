@extends('layouts.admin-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Detail Barang Keluar</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Tanggal Keluar:</strong> {{ $barang->tgl_keluar }}
                        </div>
                        <div class="mb-3">
                            <strong>Jumlah Keluar:</strong> {{ $barang->qty_keluar }}
                        </div>
                        <div class="mb-3">
                            <strong>Barang:</strong> {{ $barang->barang->merk }} {{ $barang->barang->seri }}
                        </div>


                        <a href="{{ route('barangkeluar.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection