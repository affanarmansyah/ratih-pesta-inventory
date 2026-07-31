@extends('adminlte::page')

@section('title', 'Edit Event')

@section('content_header')
    <h1>Edit Event</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('events.update', $event) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nama Customer</label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $event->customer_name) }}">
                </div>
                <div class="form-group">
                    <label>Tanggal Acara</label>
                    <input type="date" name="event_date" class="form-control" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label>Lokasi (opsional)</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $event->location) }}">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $event->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="berlangsung" {{ $event->status == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="selesai" {{ $event->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop