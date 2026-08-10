@extends('adminlte::page')

@section('title', 'Pilih Event - Barang Kembali')

@section('content_header')
    <h1>Pilih Event untuk Catat Barang Kembali</h1>
@stop

@section('content')
    <p class="text-muted">Cuma event yang masih ada barang belum kembali sepenuhnya yang muncul di sini.</p>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Tanggal Acara</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $event->customer_name }}</td>
                            <td>{{ $event->event_date->format('m-d-Y') }}</td>
                            <td><a href="{{ route('movements.create-in', $event) }}" class="btn btn-sm btn-success">Catat
                                    Barang Kembali</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Gak ada event dengan barang yang masih di luar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('movements.create-in-manual') }}" class="btn btn-secondary">Catat Tanpa Event / Penyesuaian Manual
        →</a>
@stop
