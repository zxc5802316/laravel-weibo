<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class,100)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());
        $user = User::find(1);
        $user->name = '綦晓雨';
        $user->email = 'zxl@qq.com';
        $user->is_admin = true;
        $user->password = bcrypt('admin888');
        $user->save();
    }
}
