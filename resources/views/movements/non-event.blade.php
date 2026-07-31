@extends('adminlte::page')

@section('title', 'Transaksi Non-Event')

@section('content_header')
    <h1>Transaksi Non-Event</h1>
@stop

@section('content')
    <p class="text-muted">Transaksi yang gak terkait pemesanan customer — misalnya narik barang dari luar, penyesuaian stok
        manual, dll.</p>

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
                            <td colspan="6" class="text-center">Belum ada transaksi non-event</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $movements->links() }}
        </div>
    </div>

    <a href="{{ route('movements.index') }}" class="btn btn-secondary">← Kembali</a>
@stop
