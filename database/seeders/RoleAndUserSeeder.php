<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Every seeded user uses this same password, for convenience during testing.
     */
    private const PASSWORD = 'password123';

    public function run(): void
    {
        // -----------------------------------------------------------------
        // Roles
        // -----------------------------------------------------------------
        $roles = ['Super Admin', 'Manager', 'Team Lead', 'Employee'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // -----------------------------------------------------------------
        // Departments — two, so we can also test cross-department scoping
        // (a Manager in Engineering should never see Sales' tasks, etc.)
        // -----------------------------------------------------------------
        $engineering = Department::firstOrCreate(
            ['code' => 'ENG'],
            ['name' => 'Engineering']
        );

        $sales = Department::firstOrCreate(
            ['code' => 'SALES'],
            ['name' => 'Sales']
        );

        // -----------------------------------------------------------------
        // One user per role, in Engineering, plus one extra Employee in
        // Sales so we can confirm department-based scoping actually works.
        // -----------------------------------------------------------------
        $superAdmin = $this->makeUser(
            code: 'EMP-0002',
            name: 'Superadmin',
            email: 'superadmin@midbrains.in',
            department: $engineering,
        );
        $superAdmin->syncRoles(['Super Admin']);

        $manager = $this->makeUser(
            code: 'EMP-0003',
            name: 'Manager',
            email: 'manager@midbrains.in',
            department: $engineering,
        );
        $manager->syncRoles(['Manager']);

       $employee2 = $this->makeUser(
            code: 'EMP-0006',
            name: 'Rohan',
            email: 'rohan@midbrains.in',
            department: $engineering,
            managerId: $manager->id,
        );
        $employee2->syncRoles(['Employee']);


        $this->command?->info('Seeded 1 user (password: '.self::PASSWORD.'):');
        $this->command?->table(
    ['Name', 'Email', 'Role', 'Department'],
    [
        ['Rohan', 'rohan@midbrains.in', 'Employee', 'Engineering'],
    ]
);
    }

    private function makeUser(string $code, string $name, string $email, Department $department, ?int $managerId = null): User
    {
        return User::updateOrCreate(
            ['email' => $email],
            [
                'employee_code' => $code,
                'name' => $name,
                'department_id' => $department->id,
                'manager_id' => $managerId,
                'is_active' => true,
                'password' => Hash::make(self::PASSWORD),
            ]
        );
    }
}
