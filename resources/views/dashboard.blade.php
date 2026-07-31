@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalItems }}</h3>
                    <p>Jenis Barang</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalStockAvailable }}</h3>
                    <p>Stok Tersedia</p>
                </div>
                <div class="icon"><i class="fas fa-warehouse"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalStockOut }}</h3>
                    <p>Sedang Dipakai (Event)</p>
                </div>
                <div class="icon"><i class="fas fa-truck-loading"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $lowStockItems->count() }}</h3>
                    <p>Barang Stok Menipis</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Barang stok menipis --}}
        <div class="col-md-6">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title">Barang Stok Menipis</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Kategori</th>
                                <th>Sisa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockItems as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td><span class="badge badge-danger">{{ $item->stock_available }}
                                            {{ $item->unit }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aman, gak ada yang menipis</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Selisih opname terbaru --}}
        <div class="col-md-6">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Selisih Opname (30 Hari Terakhir)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDiscrepancies as $r)
                                <tr>
                                    <td>{{ $r->session_date->format('d-m-Y') }}</td>
                                    <td>{{ $r->item->name }}</td>
                                    <td>
                                        <span class="badge {{ $r->difference < 0 ? 'badge-danger' : 'badge-info' }}">
                                            {{ $r->difference > 0 ? '+' : '' }}{{ $r->difference }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada selisih tercatat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Event terbaru --}}
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Event Terbaru</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Tanggal Acara</th>
                        <th>Status</th>
                        <th>Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEvents as $event)
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
                            <td><a href="{{ route('movements.by-event', $event) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
