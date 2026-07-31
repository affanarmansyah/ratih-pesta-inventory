@extends('adminlte::page')

@section('title', 'Data Barang')

@section('content_header')
    <h1>Data Barang</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">+ Tambah Barang</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Stok Total</th>
                        <th>Stok Tersedia</th>
                        <th width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ $item->stock_total }}</td>
                            <td>
                                <span class="badge {{ $item->stock_available > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $item->stock_available }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada barang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop