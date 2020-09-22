<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Config\Server;

class CreateConfigServersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = Server::tableName();

        Server::schemaConnection()->create($tableName, function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 64)->comment('服务器分区名称')->index();
            $table->json('mysql_agency')->comment('mysql连接配置')->nullable();

            $table->unsignedInteger('deleted')->nullable()->index();
            $table->unsignedInteger('created');
            $table->unsignedInteger('updated');
        });

        Server::dbConnection()->statement("ALTER TABLE `{$tableName}` COMMENT '配置--服务器分区配置'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Server::schemaConnection()->dropIfExists(Server::tableName());
    }
}
