@extends('adminlte::page')

@section('title', 'Riwayat Stok')

@section('content_header')
    <h1>Riwayat Barang Keluar-Masuk</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('movements.create-out') }}" class="btn btn-danger mb-3">↑ Catat Barang Keluar</a>
    <a href="{{ route('movements.create-in') }}" class="btn btn-success mb-3">↓ Catat Barang Kembali</a>
    <a href="{{ route('movements.non-event') }}" class="btn btn-secondary mb-3">Transaksi Non-Event ({{ $nonEventCount }})</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Tanggal Acara</th>
                        <th>Status</th>
                        <th>Jumlah Transaksi</th>
                        <th width="120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $event->customer_name }}</td>
                            <td>{{ $event->event_date->format('m-d-Y') }}</td>
                            <td>
                                <span
                                    class="badge {{ $event->status === 'selesai' ? 'badge-success' : ($event->status === 'berlangsung' ? 'badge-warning' : 'badge-secondary') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>{{ $event->movements_count }}</td>
                            <td>
                                <a href="{{ route('movements.by-event', $event) }}" class="btn btn-sm btn-info">Lihat
                                    Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada transaksi terkait event</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $events->links() }}
        </div>
    </div>
@stop
