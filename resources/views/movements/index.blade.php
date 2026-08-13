@extends('adminlte::page')

@section('title', 'Riwayat Stok')

@section('content_header')
    <h1>Riwayat Barang Keluar-Masuk</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('movements.create-out.select') }}" class="btn btn-danger mb-3">↑ Catat Barang Keluar</a>
    <a href="{{ route('movements.create-in.select') }}" class="btn btn-success mb-3">↓ Catat Barang Kembali</a>
    <a href="{{ route('movements.non-event') }}" class="btn btn-secondary mb-3">Transaksi Non-Event ({{ $nonEventCount }})</a>

    <form method="GET" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama customer..."
            value="{{ request('search') }}">
        <input type="date" name="from" class="form-control mr-2" value="{{ request('from') }}">
        <span class="mr-2">s/d</span>
        <input type="date" name="to" class="form-control mr-2" value="{{ request('to') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('movements.index') }}" class="btn btn-secondary ml-2">Reset</a>
    </form>

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
                            <td>{{ $event->event_date->format('d-m-Y') }}</td>
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
