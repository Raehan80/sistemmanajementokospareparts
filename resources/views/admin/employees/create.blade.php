@extends('admin.layouts.app')

@section('page-title', 'Tambah Pegawai')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Tambah Pegawai</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                @include('admin.employees._form')
                <button type="submit" class="btn btn-primary" {{ $departments->isEmpty() ? 'disabled' : '' }}>
                    Simpan
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
