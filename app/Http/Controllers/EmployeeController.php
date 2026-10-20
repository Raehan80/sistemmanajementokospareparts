<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::with('department')->orderBy('name')->get();

        return view('admin.employees.index', compact('employees'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.employees.create', [
            'employee' => new Employee,
            'departments' => $departments,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'gt:0'],
            'hired_at' => ['required', 'date_format:Y-m-d'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('status', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'gt:0'],
            'hired_at' => ['required', 'date_format:Y-m-d'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('status', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('status', 'Pegawai berhasil dihapus.');
    }
}
