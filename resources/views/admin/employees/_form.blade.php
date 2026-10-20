<div class="form-group">
    <label for="name">Nama Pegawai</label>
    <input id="name" name="name" type="text"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $employee->name) }}" maxlength="255" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="position">Jabatan</label>
    <input id="position" name="position" type="text"
           class="form-control @error('position') is-invalid @enderror"
           value="{{ old('position', $employee->position) }}" maxlength="255" required>
    @error('position')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="salary">Gaji (Rp)</label>
    <input id="salary" name="salary" type="number" min="0.01" step="0.01"
           class="form-control @error('salary') is-invalid @enderror"
           value="{{ old('salary', $employee->salary) }}" required>
    @error('salary')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="hired_at">Tanggal Bergabung</label>
    <input id="hired_at" name="hired_at" type="date"
           class="form-control @error('hired_at') is-invalid @enderror"
           value="{{ old('hired_at', $employee->hired_at?->format('Y-m-d')) }}" required>
    @error('hired_at')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="department_id">Departemen</label>
    <select id="department_id" name="department_id"
            class="form-control @error('department_id') is-invalid @enderror" required>
        <option value="">Pilih departemen</option>
        @foreach ($departments as $department)
            <option value="{{ $department->id }}"
                {{ (string) old('department_id', $employee->department_id) === (string) $department->id ? 'selected' : '' }}>
                {{ $department->name }}
            </option>
        @endforeach
    </select>
    @error('department_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if ($departments->isEmpty())
        <small class="form-text text-muted">
            Tambahkan departemen terlebih dahulu sebelum membuat data pegawai.
        </small>
    @endif
</div>
