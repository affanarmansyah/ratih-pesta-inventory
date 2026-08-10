@extends('adminlte::page')

@section('title', 'Transaksi Non-Event')

@section('content_header')
    <h1>Transaksi Non-Event</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <p class="text-muted">Transaksi yang gak terkait pemesanan customer — misalnya narik barang dari luar, penyesuaian stok
        manual, dll.</p>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
                        <th>Catatan</th>
                        <th>Dicatat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                        <tr class="{{ $m->voided_at ? 'text-muted' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->movement_date->format('m-d-Y') }}</td>
                            <td>{{ $m->item->name }}</td>
                            <td>
                                <span class="badge {{ $m->type === 'keluar' ? 'badge-danger' : 'badge-success' }}">
                                    {{ ucfirst($m->type) }}
                                </span>
                                @if ($m->voided_at)
                                    <span class="badge badge-secondary">Dibatalkan</span>
                                @endif
                            </td>
                            <td>{{ $m->quantity }}</td>
                            <td>{{ $m->condition ? ucfirst($m->condition) : '-' }}</td>
                            <td>{{ $m->notes ?? '-' }}</td>
                            <td>{{ $m->user->name ?? '-' }}</td>
                            <td>
                                @if (!$m->voided_at)
                                    <form action="{{ route('movements.void', $m) }}" method="POST"
                                        onsubmit="return confirm('Yakin batalkan transaksi ini? Stok akan disesuaikan kembali.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan</button>
                                    </form>
                                @else
                                    <small class="text-muted">oleh {{ $m->voidedBy->name ?? '-' }}</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada transaksi non-event</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $movements->links() }}
        </div>
    </div>

    <a href="{{ route('movements.index') }}" class="btn btn-secondary">← Kembali</a>
@stop
