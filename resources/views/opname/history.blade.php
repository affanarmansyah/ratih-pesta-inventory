@extends('adminlte::page')

@section('title', 'Riwayat Opname')

@section('content_header')
    <h1>Riwayat Opname</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('opname.create') }}" class="btn btn-primary mb-3">+ Opname Baru</a>

    <form method="GET" class="form-inline mb-3">
        <select name="item_id" class="form-control mr-2">
            <option value="">Semua Barang</option>
            @foreach ($items as $item)
                <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}</option>
            @endforeach
        </select>
        <input type="date" name="from" class="form-control mr-2" value="{{ request('from') }}">
        <span class="mr-2">s/d</span>
        <input type="date" name="to" class="form-control mr-2" value="{{ request('to') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('opname.history') }}" class="btn btn-secondary ml-2">Reset</a>
    </form>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Stok Sistem</th>
                        <th>Hasil Fisik</th>
                        <th>Selisih</th>
                        <th>Catatan</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $r->session_date->format('d-m-Y') }}</td>
                            <td>{{ $r->item->name }}</td>
                            <td>{{ $r->system_qty }}</td>
                            <td>{{ $r->physical_qty }}</td>
                            <td>
                                @if ($r->difference == 0)
                                    <span class="badge badge-success">Cocok</span>
                                @else
                                    <span
                                        class="badge badge-danger">{{ $r->difference > 0 ? '+' : '' }}{{ $r->difference }}</span>
                                @endif
                            </td>
                            <td>{{ $r->notes ?? '-' }}</td>
                            <td>{{ $r->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada riwayat opname</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $records->links() }}
        </div>
    </div>
@stop
