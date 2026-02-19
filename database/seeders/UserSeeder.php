<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        \Artisan::call('permission:seed');
        $group = Group::updateOrCreate(['name' => 'admin']);
        $user = User::updateOrCreate(
            [
                'email' => 'admin@paymenthub.com',
            ],[
            'name' => 'Super Admin',
            'email' => 'admin@paymenthub.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);
        $user->group_id = $group->id;
        $user->save();
        $group->permissions()->sync(Permission::pluck('id')->toArray());
    }
}
