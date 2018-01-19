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
        $faker = Faker\Factory::create(); 
 
        foreach(range(1,5) as $index)
        {
            User::create([                
                'user_name' => $faker->userName,
                'user_email' =>$faker->email,
                'user_password' =>bcrypt('secret'),
                'user_balance' => 0
            ]);
        }
    }
}