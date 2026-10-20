@extends('admin.layouts.app')

@section('page-title', 'Data Departemen')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Departemen</h1>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm"></i> Tambah Departemen
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Departemen</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Departemen</th>
                            <th scope="col">Jumlah Pegawai</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->employees_count }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning">
                                        Ubah
                                    </a>
                                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus departemen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data departemen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
