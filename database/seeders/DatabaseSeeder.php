<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@spareparts.test'],
            [
                'name' => 'Admin Toko',
                'password' => Hash::make('password'),
            ]
        );

        $departments = collect([
            'Teknologi Informasi',
            'Sumber Daya Manusia',
            'Keuangan',
        ])->mapWithKeys(fn (string $name) => [
            $name => Department::updateOrCreate(['name' => $name]),
        ]);

        $employees = [
            [
                'name' => 'Andi Saputra',
                'department' => 'Teknologi Informasi',
                'position' => 'Backend Developer',
                'salary' => 8000000,
                'hired_at' => '2022-03-01',
            ],
            [
                'name' => 'Budi Santoso',
                'department' => 'Teknologi Informasi',
                'position' => 'Frontend Developer',
                'salary' => 7500000,
                'hired_at' => '2023-01-15',
            ],
            [
                'name' => 'Citra Lestari',
                'department' => 'Sumber Daya Manusia',
                'position' => 'HR Specialist',
                'salary' => 6500000,
                'hired_at' => '2021-07-10',
            ],
            [
                'name' => 'Dian Permata',
                'department' => 'Keuangan',
                'position' => 'Akuntan',
                'salary' => 7000000,
                'hired_at' => '2020-11-20',
            ],
            [
                'name' => 'Eko Prasetyo',
                'department' => 'Sumber Daya Manusia',
                'position' => 'Recruiter',
                'salary' => 6000000,
                'hired_at' => '2024-02-05',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::updateOrCreate(
                ['name' => $employee['name']],
                [
                    'department_id' => $departments[$employee['department']]->id,
                    'position' => $employee['position'],
                    'salary' => $employee['salary'],
                    'hired_at' => $employee['hired_at'],
                ]
            );
        }
    }
}
