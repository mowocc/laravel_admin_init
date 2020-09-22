<?php
namespace Seeds\Admin\Config;

use Illuminate\Database\Seeder;
use App\Models\Admin\Config\Language;

class LanguageSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 插入数据
        Language::dbTable()->insert([
            [
                'name' => '简体中文',
                'lang' => 'zh-CN',
                'created' => time(),
                'updated' => time()
            ],
            [
                'name' => 'English',
                'lang' => 'en',
                'created' => time(),
                'updated' => time()
            ]
        ]);
    }
}
