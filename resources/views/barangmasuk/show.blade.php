@extends('layouts.admin-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Detail Barang Masuk</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Tanggal Masuk:</strong> {{ $record->tgl_masuk }}
                        </div>
                        <div class="mb-3">
                            <strong>Jumlah Masuk:</strong> {{ $record->qty_masuk }}
                        </div>
                        <div class="mb-3">
                            <strong>Barang:</strong> {{ $record->barang->merk }} {{ $record->barang->seri }}
                        </div>


                        <a href="{{ route('barangmasuk.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection