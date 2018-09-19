<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        $this->command->info('Таблица пользователей заполнена данными!');

        $this->call('UserFilesTableSeeder');
        $this->command->info('Таблица пользовательских файлов заполнена данными!');


    }
}
