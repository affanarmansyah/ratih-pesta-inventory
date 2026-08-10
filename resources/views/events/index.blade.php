@extends('adminlte::page')

@section('title', 'Data Event')

@section('content_header')
    <h1>Data Event / Pemesanan</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">+ Tambah Event</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Customer</th>
                        <th>Tanggal Acara</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Jumlah Transaksi</th>
                        <th width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $event->customer_name }}</td>
                            <td>{{ $event->event_date->format('d-m-Y') }}</td>
                            <td>{{ $event->location ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $event->status === 'selesai' ? 'badge-success' : ($event->status === 'berlangsung' ? 'badge-warning' : 'badge-secondary') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>{{ $event->movements_count }}</td>
                            <td>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus event ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada event</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop