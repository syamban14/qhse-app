<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create Permissions
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage roles']);
        Permission::firstOrCreate(['name' => 'create incident']);
        Permission::firstOrCreate(['name' => 'view all incidents']);
        Permission::firstOrCreate(['name' => 'edit incident']);
        Permission::firstOrCreate(['name' => 'delete incident']);
        Permission::firstOrCreate(['name' => 'manage actions']);
        Permission::firstOrCreate(['name' => 'create audit']);
        Permission::firstOrCreate(['name' => 'view all audits']);
        Permission::firstOrCreate(['name' => 'edit audit']);
        Permission::firstOrCreate(['name' => 'delete audit']);
        Permission::firstOrCreate(['name' => 'create document']);
        Permission::firstOrCreate(['name' => 'view all documents']);
        Permission::firstOrCreate(['name' => 'edit document']);
        Permission::firstOrCreate(['name' => 'delete document']);
        Permission::firstOrCreate(['name' => 'approve document']);
        Permission::firstOrCreate(['name' => 'investigate incident']);
        Permission::firstOrCreate(['name' => 'close incident']);

        // Create Roles and assign permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleQhseManager = Role::firstOrCreate(['name' => 'qhse_manager']);
        $roleQhseManager->givePermissionTo([
            'create incident',
            'view all incidents',
            'edit incident',
            'delete incident',
            'manage actions',
        ]);

        $roleEmployee = Role::firstOrCreate(['name' => 'employee']);
        $roleEmployee->givePermissionTo([
            'view all incidents',
            'create incident', // Employee can create incidents
            'view all documents', // Employee can view documents
        ]);

        $roleSupervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $roleSupervisor->givePermissionTo([
            'create incident',
            'view all incidents',
            'edit incident',
            'investigate incident',
            'manage actions',
            'view all audits',
            'view all documents',
        ]);

        $roleDocumentController = Role::firstOrCreate(['name' => 'document_controller']);
        $roleDocumentController->givePermissionTo([
            'create document',
            'view all documents',
            'edit document',
            'delete document',
            'approve document',
            'view all incidents', // Can view incidents to understand document needs
            'view all audits', // Can view audits for document needs
        ]);

        // Create a default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole('admin');

        // Create a default QHSE Manager user
        $qhseManagerUser = User::firstOrCreate(
            ['email' => 'qhse@example.com'],
            [
                'name' => 'QHSE Manager',
                'password' => Hash::make('password'),
            ]
        );
        $qhseManagerUser->assignRole('qhse_manager');

        // Create a default Employee user
        $employeeUser = User::firstOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Employee User',
                'password' => Hash::make('password'),
            ]
        );
        $employeeUser->assignRole('employee');

        // Create a default Supervisor user
        $supervisorUser = User::firstOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'name' => 'Supervisor User',
                'password' => Hash::make('password'),
            ]
        );
        $supervisorUser->assignRole('supervisor');

        // Create a default Document Controller user
        $docControllerUser = User::firstOrCreate(
            ['email' => 'doccontroller@example.com'],
            [
                'name' => 'Document Controller User',
                'password' => Hash::make('password'),
            ]
        );
        $docControllerUser->assignRole('document_controller');
    }
}
