@extends('adminlte::page')

@section('title', 'Opname Stok')

@section('content_header')
    <h1>Opname Stok Barang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('opname.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Tanggal Opname</label>
                    <input type="date" name="session_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Barang</th>
                            <th>Stok Sistem</th>
                            <th width="150px">Hasil Hitung Fisik</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->stock_available }} {{ $item->unit }}</td>
                                <td>
                                    <input type="number" name="physical_qty[{{ $item->id }}]" class="form-control" min="0" placeholder="kosongkan jika belum dicek">
                                </td>
                                <td>
                                    <input type="text" name="notes[{{ $item->id }}]" class="form-control" placeholder="opsional">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Simpan Opname</button>
            </form>
        </div>
    </div>
@stop