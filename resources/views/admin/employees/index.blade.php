@extends('admin.layouts.app')

@section('page-title', 'Data Pegawai')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pegawai</h1>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm"></i> Tambah Pegawai
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pegawai</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Pegawai</th>
                            <th scope="col">Departemen</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Gaji</th>
                            <th scope="col">Tanggal Bergabung</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->department->name }}</td>
                                <td>{{ $employee->position ?? '-' }}</td>
                                <td>{{ $employee->salary === null ? '-' : 'Rp '.number_format((float) $employee->salary, 0, ',', '.') }}</td>
                                <td>{{ $employee->hired_at?->format('d-m-Y') ?? '-' }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">
                                        Ubah
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus pegawai ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data pegawai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
