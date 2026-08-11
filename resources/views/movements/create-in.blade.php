@extends('adminlte::page')

@section('title', 'Catat Barang Kembali - ' . $event->customer_name)

@section('content_header')
    <h1>Catat Barang Kembali</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('movements.store-in') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="form-group">
                    <label>Event</label>
                    <input type="text" class="form-control" value="{{ $event->customer_name }}" disabled>
                </div>

                <div class="form-group">
                    <label>Barang</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" data-outstanding="{{ $outstanding[$item->id] }}">
                                {{ $item->name }} (sisa di luar: {{ $outstanding[$item->id] }} {{ $item->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah Kondisi Baik</label>
                            <input type="number" name="qty_baik" id="qty_baik" class="form-control" min="0"
                                value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah Rusak</label>
                            <input type="number" name="qty_rusak" id="qty_rusak" class="form-control" min="0"
                                value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah Hilang</label>
                            <input type="number" name="qty_hilang" id="qty_hilang" class="form-control" min="0"
                                value="0">
                        </div>
                    </div>
                </div>
                <small id="sisaInfo" class="text-muted"></small>
                @error('qty_baik')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-group mt-2">
                    <label>Tanggal</label>
                    <input type="date" name="movement_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label>Catatan (opsional)</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('movements.create-in.select') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('item_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const max = selected.getAttribute('data-outstanding') || 0;
            document.getElementById('sisaInfo').innerText = max ? `Sisa maksimal yang bisa dicatat: ${max}` : '';
            ['qty_baik', 'qty_rusak', 'qty_hilang'].forEach(id => {
                document.getElementById(id).setAttribute('max', max);
            });
        });
    </script>
@stop
