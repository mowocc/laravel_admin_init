<?php
namespace Seeds\Admin\Auth;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\Auth\User;
use Illuminate\Support\Facades\Auth;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 超级管理员
        $user = User::firstOrCreate([
            'email' => 'admin@admin.com'
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('aaaaaaaa'),
            'email_verified_at' => now()
        ]);
        // 分配角色
        $user->assignSuperAdmin();

        // 其他账号
        User::dbTable()->insert([
            [
                'name' => 'mowocc',
                'email' => 'mowocc@admin.com',
                'password' => Hash::make('aaaaaaaa'),
                'email_verified_at' => now(),
                'created' => time(),
                'updated' => time()
            ],
            [
                'name' => 'test',
                'email' => 'test@admin.com',
                'password' => Hash::make('aaaaaaaa'),
                'email_verified_at' => now(),
                'created' => time(),
                'updated' => time()
            ]
        ]);

        // 生成多条随机数据
        factory(\App\Models\Admin\Auth\User::class, 50)->create();
    }
}
