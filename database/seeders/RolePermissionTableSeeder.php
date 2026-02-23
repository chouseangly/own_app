<?php

namespace Database\Seeders;

use App\Enums\Role as EnumRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Explicitly find the Admin role using the 'sanctum' guard defined in RoleTableSeeder
        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'sanctum')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(Permission::all());
        }

        // 2. Find Manager role (Role ID 3) for the sanctum guard
        $branchManager = Role::where('name', 'Manager')->where('guard_name', 'sanctum')->first();
        if ($branchManager) {
            $branchManagerPermissions = [
                'dashboard',
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

            // Note: whereIn expects a flat array of strings, not nested arrays
            $permissions = Permission::whereIn('name', $branchManagerPermissions)
                                     ->where('guard_name', 'sanctum')
                                     ->get();
            $branchManager->givePermissionTo($permissions);
        }

        // 3. Find POS Operator role (Role ID 4) for the sanctum guard
        $posOperatorManager = Role::where('name', 'POS Operator')->where('guard_name', 'sanctum')->first();
        if ($posOperatorManager) {
            $posOperatorManagerPermissions = [
                'dashboard',
                'pos',
                'pos-orders'
            ];

            $permissions = Permission::whereIn('name', $posOperatorManagerPermissions)
                                     ->where('guard_name', 'sanctum')
                                     ->get();
            $posOperatorManager->givePermissionTo($permissions);
        }
    }
}
