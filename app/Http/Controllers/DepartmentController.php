<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('employees')->orderBy('name')->get();

        return view('admin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('admin.departments.create', ['department' => new Department()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('status', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Department $department): View
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('status', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->employees()->exists()) {
            return redirect()->route('departments.index')
                ->with('error', 'Departemen tidak dapat dihapus karena masih memiliki pegawai.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('status', 'Departemen berhasil dihapus.');
    }
}
