<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        # Create permissions
        // Map
        Permission::findOrCreate('view-map');
        Permission::findOrCreate('find-map');
        Permission::findOrCreate('classification-map');

        // Boarding House Data
        Permission::findOrCreate('view-boarding-house');
        Permission::findOrCreate('create-boarding-house');
        Permission::findOrCreate('update-boarding-house');
        Permission::findOrCreate('delete-boarding-house');

        // Boarding Admin
        Permission::findOrCreate('view-boarding-admin');
        Permission::findOrCreate('create-boarding-admin');
        Permission::findOrCreate('update-boarding-admin');
        Permission::findOrCreate('delete-boarding-admin');


        # Create roles
        $superadmin = Role::findOrCreate('superadmin');
        $admin = Role::findOrCreate('admin');
        $user = Role::findOrCreate('user');


        # Assign permissions to roles
        $superadmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'view-map',
            'view-boarding-house',
            'create-boarding-house',
            'update-boarding-house',
            'delete-boarding-house',
        ]);

        $user->givePermissionTo([
            'view-map',
            'find-map',
            'classification-map',
            'view-boarding-house',
        ]);
    }
}
