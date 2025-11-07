@extends('layouts.app')

@section('title', 'Dashboard Infratik')
@section('page-title', 'Dashboard Infratik')
@section('page-subtitle', 'Monitoring aset server & website bidang Infratik')

@section('content')
    <div class="alert alert-info">
        Selamat datang, {{ Auth::user()->name }}! Ini dashboard khusus Bidang Infratik.
    </div>
@endsection
