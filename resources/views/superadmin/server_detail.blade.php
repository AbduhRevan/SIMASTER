@extends('layouts.app')
@section('title','Detail Server')
@section('content')
<div class="container">
    <h3>Detail Server: {{ $server->nama_server }}</h3>
    <table class="table table-bordered">
        <tr><th>Brand</th><td>{{ $server->brand ?? '-' }}</td></tr>
        <tr><th>Slot</th><td>{{ $server->u_slot ?? '-' }}</td></tr>
        <tr><th>Rak</th><td>{{ $server->rak ? $server->rak->nama_rak : '-' }}</td></tr>
        <tr><th>Bidang</th><td>{{ $server->bidang ? $server->bidang->nama_bidang : '-' }}</td></tr>
        <tr><th>Satker</th><td>{{ $server->satker ? $server->satker->nama_satker : '-' }}</td></tr>
        <tr><th>Website</th><td>{{ $server->website ? $server->website->nama_website : '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $server->power_status }}</td></tr>
        <tr><th>Keterangan</th><td>{{ $server->keterangan ?? '-' }}</td></tr>
        <tr><th>Spesifikasi</th><td>{{ $server->spesifikasi ?? '-' }}</td></tr>
    </table>
    <a href="{{ route('superadmin.server.index') }}" class="btn btn-light">Kembali</a>
</div>
@endsection
