<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    public function run()
    {
        // 1. Define ALL permissions used in your application
        $allPermissionNames = [
            'dashboard',
            'settings',
            'pos',
            'pos-orders',
            'online-orders',
            'push-notifications',
            'push-notifications_create',
            'push-notifications_edit',
            'push-notifications_delete',
            'push-notifications_show',
            'subscribers',
            'customers',
            'customers_create',
            'customers_edit',
            'customers_delete',
            'customers_show',
            'employees',
            'employees_create',
            'employees_edit',
            'employees_delete',
            'employees_show',
            'transactions',
            'sales-report'
        ];

        // 2. CREATE the permissions in the database first
        foreach ($allPermissionNames as $name) {
            Permission::findOrCreate($name, 'sanctum');
        }

        // 3. Grant ALL permissions to Admin
        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'sanctum')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }

        // 4. Assign specific permissions to Manager
        $branchManager = Role::where('name', 'Manager')->where('guard_name', 'sanctum')->first();
        if ($branchManager) {
            $managerPermissions = Permission::whereIn('name', [
                'dashboard',
                'settings',
                'pos',
                'pos-orders',
                'online-orders',
                'push-notifications',
                'subscribers',
                'customers',
                'employees',
                'transactions',
                'sales-report'
            ])->where('guard_name', 'sanctum')->get();

            $branchManager->syncPermissions($managerPermissions);
        }

        // 5. Assign specific permissions to POS Operator
        $posOperator = Role::where('name', 'POS Operator')->where('guard_name', 'sanctum')->first();
        if ($posOperator) {
            $posPermissions = Permission::whereIn('name', [
                'dashboard',
                'pos',
                'pos-orders'
            ])->where('guard_name', 'sanctum')->get();

            $posOperator->syncPermissions($posPermissions);
        }
    }
}
