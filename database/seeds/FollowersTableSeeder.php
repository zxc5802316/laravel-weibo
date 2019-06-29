<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $user = User::find(1);
        $user_id = $user->id;

        $follows = $users->slice(1);
        $follows_ids = $follows->pluck('id')->toArray();

        $user->follow($follows_ids);

        foreach ($follows as $follow){
            $follow->follow($user_id);
        }

    }
}
