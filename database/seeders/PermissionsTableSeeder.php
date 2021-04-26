<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'covid_resource_access',
            ],
            [
                'id'    => 18,
                'title' => 'category_create',
            ],
            [
                'id'    => 19,
                'title' => 'category_edit',
            ],
            [
                'id'    => 20,
                'title' => 'category_show',
            ],
            [
                'id'    => 21,
                'title' => 'category_delete',
            ],
            [
                'id'    => 22,
                'title' => 'category_access',
            ],
            [
                'id'    => 23,
                'title' => 'resource_create',
            ],
            [
                'id'    => 24,
                'title' => 'resource_edit',
            ],
            [
                'id'    => 25,
                'title' => 'resource_show',
            ],
            [
                'id'    => 26,
                'title' => 'resource_delete',
            ],
            [
                'id'    => 27,
                'title' => 'resource_access',
            ],
            [
                'id'    => 28,
                'title' => 'city_create',
            ],
            [
                'id'    => 29,
                'title' => 'city_edit',
            ],
            [
                'id'    => 30,
                'title' => 'city_show',
            ],
            [
                'id'    => 31,
                'title' => 'city_delete',
            ],
            [
                'id'    => 32,
                'title' => 'city_access',
            ],
            [
                'id'    => 33,
                'title' => 'state_create',
            ],
            [
                'id'    => 34,
                'title' => 'state_edit',
            ],
            [
                'id'    => 35,
                'title' => 'state_show',
            ],
            [
                'id'    => 36,
                'title' => 'state_delete',
            ],
            [
                'id'    => 37,
                'title' => 'state_access',
            ],
            [
                'id'    => 38,
                'title' => 'worldinfo_access',
            ],
            [
                'id'    => 39,
                'title' => 'country_create',
            ],
            [
                'id'    => 40,
                'title' => 'country_edit',
            ],
            [
                'id'    => 41,
                'title' => 'country_show',
            ],
            [
                'id'    => 42,
                'title' => 'country_delete',
            ],
            [
                'id'    => 43,
                'title' => 'country_access',
            ],
            [
                'id'    => 44,
                'title' => 'new_req_create',
            ],
            [
                'id'    => 45,
                'title' => 'new_req_edit',
            ],
            [
                'id'    => 46,
                'title' => 'new_req_show',
            ],
            [
                'id'    => 47,
                'title' => 'new_req_delete',
            ],
            [
                'id'    => 48,
                'title' => 'new_req_access',
            ],
            [
                'id'    => 49,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
