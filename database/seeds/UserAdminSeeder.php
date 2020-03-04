<?php

use App\Eloquent\RoleUSer;
use App\Eloquent\User;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAdmin = User::updateOrCreate(
            [
                'email' => config('settings.user.admin.email'),
            ],
            [
                'name' => config('settings.user.admin.name'),
                'email' => config('settings.user.admin.email'),
                'password' => bcrypt(config('settings.user.admin.password')),
                'employee_code' => config('settings.user.admin.employee_code'),
                'avatar' => config('app.url') . '/' . config('settings.user.admin.avatar'),
                'position' => config('settings.user.admin.position'),
            ]
        );
        RoleUSer::updateOrCreate(
            [
                'role_id' => 1,
                'user_id' => $userAdmin->id
            ],
            [
                'role_id' => 1,
                'user_id' => $userAdmin->id
            ]
        );
    }
}
