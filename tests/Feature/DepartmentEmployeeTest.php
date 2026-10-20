<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DepartmentEmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_department_and_employee_relationships_are_available(): void
    {
        $department = Department::create(['name' => 'Penjualan']);
        $employee = Employee::create([
            'name' => 'Budi',
            'position' => 'Mekanik',
            'salary' => 5000000,
            'hired_at' => '2024-01-15',
            'department_id' => $department->id,
        ]);

        $this->assertTrue($department->employees->contains($employee));
        $this->assertTrue($employee->department->is($department));
    }

    public function test_employee_list_shows_department_name(): void
    {
        $department = Department::create(['name' => 'Gudang']);
        Employee::create([
            'name' => 'Siti',
            'position' => 'Mekanik',
            'salary' => 5000000,
            'hired_at' => '2024-01-15',
            'department_id' => $department->id,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('employees.index'))
            ->assertOk()
            ->assertSee('Siti')
            ->assertSee('Gudang');
    }

    public function test_employee_list_requires_authentication(): void
    {
        $this->get(route('employees.index'))
            ->assertRedirect(route('login'));
    }

    public function test_department_can_be_created_updated_and_deleted_when_unused(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('departments.store'), ['name' => 'Penjualan'])
            ->assertRedirect(route('departments.index'));

        $department = Department::where('name', 'Penjualan')->firstOrFail();

        $this->put(route('departments.update', $department), ['name' => 'Operasional'])
            ->assertRedirect(route('departments.index'));
        $this->assertDatabaseHas('departments', ['id' => $department->id, 'name' => 'Operasional']);

        $this->delete(route('departments.destroy', $department))
            ->assertRedirect(route('departments.index'));
        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }

    public function test_department_with_employees_cannot_be_deleted(): void
    {
        $department = Department::create(['name' => 'Gudang']);
        Employee::create([
            'name' => 'Siti',
            'position' => 'Mekanik',
            'salary' => 5000000,
            'hired_at' => '2024-01-15',
            'department_id' => $department->id,
        ]);

        $this->actingAs(User::factory()->create())
            ->delete(route('departments.destroy', $department))
            ->assertRedirect(route('departments.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('departments', ['id' => $department->id]);
    }

    public function test_employee_can_be_created_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $department = Department::create(['name' => 'Penjualan']);
        $anotherDepartment = Department::create(['name' => 'Operasional']);

        $this->post(route('employees.store'), [
            'name' => 'Budi',
            'position' => 'Mekanik',
            'salary' => 5000000,
            'hired_at' => '2024-01-15',
            'department_id' => $department->id,
        ])->assertRedirect(route('employees.index'));

        $employee = Employee::where('name', 'Budi')->firstOrFail();

        $this->put(route('employees.update', $employee), [
            'name' => 'Budi Santoso',
            'position' => 'Kepala Mekanik',
            'salary' => 6500000,
            'hired_at' => '2023-12-01',
            'department_id' => $anotherDepartment->id,
        ])->assertRedirect(route('employees.index'));
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'Budi Santoso',
            'position' => 'Kepala Mekanik',
            'salary' => 6500000,
            'hired_at' => '2023-12-01',
            'department_id' => $anotherDepartment->id,
        ]);

        $this->delete(route('employees.destroy', $employee))
            ->assertRedirect(route('employees.index'));
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_employee_requires_an_existing_department(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('employees.store'), [
                'name' => 'Budi',
                'position' => 'Mekanik',
                'salary' => 5000000,
                'hired_at' => '2024-01-15',
                'department_id' => 999,
            ])
            ->assertSessionHasErrors('department_id');

        $this->assertDatabaseCount('employees', 0);
    }

    public function test_employee_fields_are_required_and_validated(): void
    {
        $this->actingAs(User::factory()->create());
        $department = Department::create(['name' => 'Bengkel']);

        $this->post(route('employees.store'), [
            'name' => '',
            'position' => '',
            'salary' => 0,
            'hired_at' => 'not-a-date',
            'department_id' => $department->id,
        ])->assertSessionHasErrors(['name', 'position', 'salary', 'hired_at']);

        $this->post(route('employees.store'), [
            'name' => 'Budi',
            'position' => 'Mekanik',
            'salary' => 'bukan angka',
            'hired_at' => '2024-02-30',
            'department_id' => $department->id,
        ])->assertSessionHasErrors(['salary', 'hired_at']);

        $this->assertDatabaseCount('employees', 0);
    }

    public function test_department_name_is_required(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('departments.store'), ['name' => ''])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('departments', 0);
    }

    public function test_database_seeder_creates_test_user_and_related_sample_data(): void
    {
        $this->seed();

        $user = User::where('email', 'admin@spareparts.test')->firstOrFail();
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertDatabaseCount('departments', 3);
        $this->assertDatabaseCount('employees', 5);
        $this->assertSame(
            5,
            Employee::whereHas('department')->count()
        );

        $this->actingAs($user)
            ->get(route('employees.index'))
            ->assertOk()
            ->assertSee('Andi Saputra')
            ->assertSee('Backend Developer')
            ->assertSee('Teknologi Informasi')
            ->assertSee('8.000.000');
    }
}
