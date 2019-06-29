<?php

use Illuminate\Database\Seeder;
use App\Models\{User,Status};
class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = User::pluck('id');
        $faker = app(\Faker\Generator::class);

        $statuses = factory(Status::class,1000)->make()->each(function ($status) use ($user_ids,$faker){
            $status->user_id = $faker->randomElement($user_ids);
        });
        Status::insert($statuses->toArray());
    }
}
