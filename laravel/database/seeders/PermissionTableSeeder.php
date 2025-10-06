<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [


            [
                'group' => 'super',
                'name' => 'super',
                'display_name' => 'super',

            ],


            [
                'group' => 'reports',
                'name' => 'reports-list',
                'display_name' => 'list',

            ],

            [
                'group' => 'subscription',
                'name' => 'subscription-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'subscription',
                'name' => 'subscription-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'subscription',
                'name' => 'subscription-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'subscription',
                'name' => 'subscription-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'schools',
                'name' => 'schools-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'schools',
                'name' => 'schools-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'schools',
                'name' => 'schools-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'schools',
                'name' => 'schools-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'schools',
                'name' => 'schools-destroy',
                'display_name' => 'delete',

            ],


            [
                'group' => 'attendants',
                'name' => 'attendants-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'attendants',
                'name' => 'attendants-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'attendants',
                'name' => 'attendants-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'attendants',
                'name' => 'attendants-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'attendants',
                'name' => 'attendants-destroy',
                'display_name' => 'delete',

            ],


            [
                'group' => 'students',
                'name' => 'students-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'students',
                'name' => 'students-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'students',
                'name' => 'students-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'students',
                'name' => 'students-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'students',
                'name' => 'students-destroy',
                'display_name' => 'delete',

            ],



            [
                'group' => 'buses',
                'name' => 'buses-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'buses',
                'name' => 'buses-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'buses',
                'name' => 'buses-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'buses',
                'name' => 'buses-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'buses',
                'name' => 'buses-destroy',
                'display_name' => 'delete',

            ],

            [
                'group' => 'trips',
                'name' => 'trips-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'trips',
                'name' => 'trips-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'trips',
                'name' => 'trips-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'trips',
                'name' => 'trips-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'trips',
                'name' => 'trips-destroy',
                'display_name' => 'delete',

            ],

            [
                'group' => 'parents',
                'name' => 'parents-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'parents',
                'name' => 'parents-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'parents',
                'name' => 'parents-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'parents',
                'name' => 'parents-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'parents',
                'name' => 'parents-destroy',
                'display_name' => 'delete',

            ],



            [
                'group' => 'grades',
                'name' => 'grades-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'grades',
                'name' => 'grades-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'grades',
                'name' => 'grades-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'grades',
                'name' => 'grades-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'grades',
                'name' => 'grades-destroy',
                'display_name' => 'delete',

            ],


            [
                'group' => 'classrooms',
                'name' => 'classrooms-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'classrooms',
                'name' => 'classrooms-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'classrooms',
                'name' => 'classrooms-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'classrooms',
                'name' => 'classrooms-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'classrooms',
                'name' => 'classrooms-destroy',
                'display_name' => 'delete',

            ],



            [
                'group' => 'attendances',
                'name' => 'attendances-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'attendances',
                'name' => 'attendances-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'attendances',
                'name' => 'attendances-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'attendances',
                'name' => 'attendances-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'attendances',
                'name' => 'attendances-destroy',
                'display_name' => 'delete',

            ],




            [
                'group' => 'absences',
                'name' => 'absences-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'absences',
                'name' => 'absences-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'absences',
                'name' => 'absences-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'absences',
                'name' => 'absences-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'absences',
                'name' => 'absences-destroy',
                'display_name' => 'delete',

            ],

            [
                'group' => 'addresses',
                'name' => 'addresses-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'addresses',
                'name' => 'addresses-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'addresses',
                'name' => 'addresses-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'addresses',
                'name' => 'addresses-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'addresses',
                'name' => 'addresses-destroy',
                'display_name' => 'delete',

            ],

            [
                'group' => 'school_messages',
                'name' => 'school_messages-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'school_messages',
                'name' => 'school_messages-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'school_messages',
                'name' => 'school_messages-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'school_messages',
                'name' => 'school_messages-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'school_messages',
                'name' => 'school_messages-destroy',
                'display_name' => 'delete',

            ],

            [
                'group' => 'roles',
                'name' => 'roles-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'roles',
                'name' => 'roles-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'roles',
                'name' => 'roles-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'roles',
                'name' => 'roles-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'roles',
                'name' => 'roles-destroy',
                'display_name' => 'delete',

            ],



            [
                'group' => 'staff',
                'name' => 'staff-list',
                'display_name' => 'list',

            ],
            [
                'group' => 'staff',
                'name' => 'staff-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'staff',
                'name' => 'staff-create',
                'display_name' => 'create',

            ],
            [
                'group' => 'staff',
                'name' => 'staff-edit',
                'display_name' => 'edit',

            ],
            [
                'group' => 'staff',
                'name' => 'staff-destroy',
                'display_name' => 'delete',

            ],



            [
                'group' => 'settings',
                'name' => 'settings-show',
                'display_name' => 'show',

            ],
            [
                'group' => 'settings',
                'name' => 'settings-edit',
                'display_name' => 'edit',

            ],








        ];

        foreach ($permissions as $name => $permission) {
            Permission::create(['guard_name' => 'web', 'group' => $permission['group'], 'name' => $permission['name'], 'display_name' => $permission['display_name']]);
        }

        $role = Role::create(['name' => 'Super Admin']);
        $role->syncPermissions(Permission::get()->pluck('name'));
    }
}
