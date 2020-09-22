<?php
namespace Seeds\Admin\Config;

use Illuminate\Database\Seeder;
use App\Models\Admin\Config\Server;

class ServerSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 插入数据
        Server::dbTable()->insert([
            [
                'name' => 'Game-Server-01',
                'mysql_agency' => json_encode([
                    'host' => env('MYSQL_AGENCY_HOST', '127.0.0.1'),
                    'port' => env('MYSQL_AGENCY_PORT', '3306'),
                    'database' => 'bl_agency_01',
                    'username' => env('MYSQL_AGENCY_USERNAME', 'root'),
                    'password' => env('MYSQL_AGENCY_PASSWORD', '')
                ]),
                'created' => time(),
                'updated' => time()
            ],
            [
                'name' => 'Game-Server-02',
                'mysql_agency' => json_encode([
                    'host' => env('MYSQL_AGENCY_HOST', '127.0.0.1'),
                    'port' => env('MYSQL_AGENCY_PORT', '3306'),
                    'database' => 'bl_agency_02',
                    'username' => env('MYSQL_AGENCY_USERNAME', 'root'),
                    'password' => env('MYSQL_AGENCY_PASSWORD', '')
                ]),
                'created' => time(),
                'updated' => time()
            ],
            [
                'name' => 'Game-Server-03',
                'mysql_agency' => json_encode([
                    'host' => env('MYSQL_AGENCY_HOST', '127.0.0.1'),
                    'port' => env('MYSQL_AGENCY_PORT', '3306'),
                    'database' => 'bl_agency_03',
                    'username' => env('MYSQL_AGENCY_USERNAME', 'root'),
                    'password' => env('MYSQL_AGENCY_PASSWORD', '')
                ]),
                'created' => time(),
                'updated' => time()
            ]
        ]);
    }
}
