<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class EmployeeSeeder extends Seeder
{

    public function run(): void
    {

        if (!DB::table('users')->where('email', 'admin@abz.agency')->exists()) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@abz.agency',
                'password' => Hash::make('admin'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $faker = Faker::create();

        $positions = [];
        for ($i = 0; $i < 10; $i++) {
            $positions[] = DB::table('positions')->insertGetId([
                'name' => $faker->jobTitle,
                'admin_created_id' => 1,
                'admin_updated_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        $managers = [];
        for ($i = 1; $i <= 50000; $i++) {
            $managerId = null;
            $level = 1;

            if ($i > 5) {
                $managerId = $faker->randomElement($managers);

                $manager = DB::table('employees')->find($managerId);
                if ($manager !== null) {
                    $level = $manager->level + 1;
                }

                if ($level > 5) {
                    continue;
                }
            }

            $employeeId = DB::table('employees')->insertGetId([
                'name' => $faker->name,
                'position_id' => $faker->randomElement($positions),
                'date_of_employment' => $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
                'phone' => $faker->unique()->e164PhoneNumber,
                'email' => $faker->unique()->safeEmail,
                'salary' => $faker->numberBetween(0, 500000),
                'photo_path' => null,
                'manager_id' => $managerId,
                'level' => $level,
                'admin_created_id' => 1,
                'admin_updated_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($level < 5) {
                $managers[] = $employeeId;
            }
        }
    }
}
