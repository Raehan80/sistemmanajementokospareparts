@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Selamat datang</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">Halo, {{ Auth::user()->name }}.</p>
                    <p class="mb-0">
                        Anda berhasil masuk ke sistem manajemen toko spare parts.
                        Gunakan menu di samping untuk mengakses bagian aplikasi.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
