@extends('admin.layout')

@section('content')

@include('admin.dashboard.components.stats', ['stats' => $stats])

@include('admin.dashboard.components.chart')

@include('admin.dashboard.components.verifikasi', ['verifikasi' => $verifikasi])

@endsection