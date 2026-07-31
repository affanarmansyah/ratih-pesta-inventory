@extends('adminlte::page')

@section('title', 'Riwayat Opname')

@section('content_header')
    <h1>Riwayat Opname</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('opname.create') }}" class="btn btn-primary mb-3">+ Opname Baru</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Stok Sistem</th>
                        <th>Hasil Fisik</th>
                        <th>Selisih</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ $r->session_date->format('d-m-Y') }}</td>
                            <td>{{ $r->item->name }}</td>
                            <td>{{ $r->system_qty }}</td>
                            <td>{{ $r->physical_qty }}</td>
                            <td>
                                @if($r->difference == 0)
                                    <span class="badge badge-success">Cocok</span>
                                @else
                                    <span class="badge badge-danger">{{ $r->difference > 0 ? '+' : '' }}{{ $r->difference }}</span>
                                @endif
                            </td>
                            <td>{{ $r->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada riwayat opname</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $records->links() }}
        </div>
    </div>
@stop