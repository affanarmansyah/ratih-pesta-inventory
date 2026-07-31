@extends('adminlte::page')

@section('title', 'Edit Barang')

@section('content_header')
    <h1>Edit Barang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('items.update', $item) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category_id" class="form-control">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}">
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="unit" class="form-control" value="{{ old('unit', $item->unit) }}">
                </div>

                <div class="form-group">
                    <label>Stok Total</label>
                    <input type="number" name="stock_total" class="form-control"
                        value="{{ old('stock_total', $item->stock_total) }}">
                    <small class="text-muted">Hati-hati mengubah ini kalau barang sudah pernah punya riwayat
                        transaksi</small>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
