<div class="form-group">
    <label for="name">Nama Departemen</label>
    <input id="name" name="name" type="text"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $department->name) }}" maxlength="255" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
