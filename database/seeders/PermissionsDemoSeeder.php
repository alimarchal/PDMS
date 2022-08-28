<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'data entry']);
        $role1->givePermissionTo('edit articles');
        $role1->givePermissionTo('publish articles');

        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('delete articles');
        $role2->givePermissionTo('publish articles');

        $role3 = Role::create(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Data Entry User',
            'email' => 'dataentry@qazisolutions.com',
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Admin User',
            'email' => 'admin@qazisolutions.com',
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Super-Admin User',
            'email' => 'superadmin@qazisolutions.com',
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole($role3);
    }
}
