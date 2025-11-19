@extends('layouts.bidang')

@section('title', 'Dashboard Banglola')
@section('page-title', 'Dashboard Banglola')
@section('page-subtitle', 'Monitoring aset server & website bidang Banglola')

@section('content')
    <div class="alert alert-info">
        Selamat datang, {{ Auth::user()->name }}! Ini dashboard khusus Bidang Banglola.
    </div>
@endsection
