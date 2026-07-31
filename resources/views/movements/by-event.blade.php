@extends('adminlte::page')

@section('title', 'Detail Transaksi - ' . $event->customer_name)

@section('content_header')
    <h1>Detail Transaksi: {{ $event->customer_name }}</h1>
@stop

@section('content')
    <div class="card card-outline card-primary mb-3">
        <div class="card-body">
            <strong>Tanggal Acara:</strong> {{ $event->event_date->format('m-d-Y') }}<br>
            <strong>Lokasi:</strong> {{ $event->location ?? '-' }}<br>
            <strong>Status:</strong> {{ ucfirst($event->status) }}
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td>{{ $m->movement_date->format('m-d-Y') }}</td>
                            <td>{{ $m->item->name }}</td>
                            <td>
                                <span class="badge {{ $m->type === 'keluar' ? 'badge-danger' : 'badge-success' }}">
                                    {{ ucfirst($m->type) }}
                                </span>
                            </td>
                            <td>{{ $m->quantity }}</td>
                            <td>{{ $m->condition ? ucfirst($m->condition) : '-' }}</td>
                            <td>{{ $m->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('movements.index') }}" class="btn btn-secondary">← Kembali ke Daftar Event</a>
@stop
