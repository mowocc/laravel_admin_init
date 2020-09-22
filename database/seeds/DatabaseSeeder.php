<?php
use Illuminate\Database\Seeder;
use Seeds\Admin\Config\LanguageSeeder as AdminAuthLanguageSeeder;
use Seeds\Admin\Config\MenuSeeder as AdminAuthMenuSeeder;
use Seeds\Admin\Config\ServerSeeder as AdminAuthServerSeeder;
use Seeds\Admin\Auth\UserSeeder as AdminAuthUserSeeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminAuthLanguageSeeder::class,
            AdminAuthMenuSeeder::class,
            AdminAuthServerSeeder::class,
            AdminAuthUserSeeder::class,
        ]);
    }
}
