<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 5000) as $index) {
            $hashFile = $faker->md5;
            DB::table('user_files')->insert([
                'description' => $faker->text($maxNbChars = 200),
                'email' => $faker->email,
                'hash_user' => $faker->md5,
                'hash_file' => $hashFile,
                'file_name' => $faker->regexify('[a-zA-Z0-9]{4,12}') . "." . $faker->fileExtension,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
