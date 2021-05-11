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
                'title' => 'category_create',
            ],
            [
                'id'    => 18,
                'title' => 'category_edit',
            ],
            [
                'id'    => 19,
                'title' => 'category_show',
            ],
            [
                'id'    => 20,
                'title' => 'category_delete',
            ],
            [
                'id'    => 21,
                'title' => 'category_access',
            ],
            [
                'id'    => 22,
                'title' => 'resource_create',
            ],
            [
                'id'    => 23,
                'title' => 'resource_edit',
            ],
            [
                'id'    => 24,
                'title' => 'resource_show',
            ],
            [
                'id'    => 25,
                'title' => 'resource_delete',
            ],
            [
                'id'    => 26,
                'title' => 'resource_access',
            ],
            [
                'id'    => 27,
                'title' => 'city_create',
            ],
            [
                'id'    => 28,
                'title' => 'city_edit',
            ],
            [
                'id'    => 29,
                'title' => 'city_show',
            ],
            [
                'id'    => 30,
                'title' => 'city_delete',
            ],
            [
                'id'    => 31,
                'title' => 'city_access',
            ],
            [
                'id'    => 32,
                'title' => 'state_create',
            ],
            [
                'id'    => 33,
                'title' => 'state_edit',
            ],
            [
                'id'    => 34,
                'title' => 'state_show',
            ],
            [
                'id'    => 35,
                'title' => 'state_delete',
            ],
            [
                'id'    => 36,
                'title' => 'state_access',
            ],
            [
                'id'    => 37,
                'title' => 'worldinfo_access',
            ],
            [
                'id'    => 38,
                'title' => 'country_create',
            ],
            [
                'id'    => 39,
                'title' => 'country_edit',
            ],
            [
                'id'    => 40,
                'title' => 'country_show',
            ],
            [
                'id'    => 41,
                'title' => 'country_delete',
            ],
            [
                'id'    => 42,
                'title' => 'country_access',
            ],
            [
                'id'    => 43,
                'title' => 'new_req_create',
            ],
            [
                'id'    => 44,
                'title' => 'new_req_edit',
            ],
            [
                'id'    => 45,
                'title' => 'new_req_show',
            ],
            [
                'id'    => 46,
                'title' => 'new_req_delete',
            ],
            [
                'id'    => 47,
                'title' => 'new_req_access',
            ],
            [
                'id'    => 48,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 49,
                'title' => 'sub_category_create',
            ],
            [
                'id'    => 50,
                'title' => 'sub_category_edit',
            ],
            [
                'id'    => 51,
                'title' => 'sub_category_show',
            ],
            [
                'id'    => 52,
                'title' => 'sub_category_delete',
            ],
            [
                'id'    => 53,
                'title' => 'sub_category_access',
            ],
        ];


        Permission::insert($permissions);
    }
}
