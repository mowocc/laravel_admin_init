<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Auth\UserConfig;

class CreateAuthUserConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = UserConfig::tableName();

        UserConfig::schemaConnection()->create($tableName, function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->comment('用户ID')->unique();
            $table->foreignId('server_id')->comment('服务器ID')->nullable()->index();
            $table->string('language_lang', 16)->comment('语言code')->nullable()->index();

            $table->unsignedInteger('created');
            $table->unsignedInteger('updated');
        });

        UserConfig::dbConnection()->statement("ALTER TABLE `{$tableName}` COMMENT '用户--个人设置'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        UserConfig::schemaConnection()->dropIfExists(UserConfig::tableName());
    }
}
