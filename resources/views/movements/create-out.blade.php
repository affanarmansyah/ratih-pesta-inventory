@extends('adminlte::page')

@section('title', 'Catat Barang Keluar')

@section('content_header')
    <h1>Catat Barang Keluar</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('movements.store-out') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Barang</label>
                    <select name="item_id" class="form-control">
                        <option value="">-- Tidak ada barang --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} (tersedia: {{ $item->stock_available }}
                                {{ $item->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Untuk Event (opsional)</label>
                    <select name="event_id" class="form-control">
                        <option value="">-- Tidak terkait event --</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}">{{ $event->customer_name }} |
                                {{ $event->event_date->format('d-m-Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Jumlah Keluar</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                        min="1">
                    @error('quantity')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
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
