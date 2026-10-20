@extends('admin.layouts.app')

@section('page-title', 'Ubah Departemen')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Ubah Departemen</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.departments._form')
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
