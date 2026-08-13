@extends('adminlte::page')

@section('title', 'Catat Barang Kembali (Manual)')

@section('content_header')
    <h1>Catat Barang Kembali - Tanpa Event</h1>
@stop

@section('content')
    <p class="text-muted">Gunakan form ini untuk barang yang gak terkait pemesanan customer (misal: penyesuaian stok, barang
        dari luar).</p>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('movements.store-in') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Barang</label>
                    <select name="item_id" class="form-control">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah Kondisi Baik</label>
                            <input type="number" name="qty_baik" class="form-control" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah Rusak</label>
                            <input type="number" name="qty_rusak" class="form-control" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah Hilang</label>
                            <input type="number" name="qty_hilang" class="form-control" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <label>Tanggal</label>
                    <input type="date" name="movement_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label>Catatan (opsional)</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('movements.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
