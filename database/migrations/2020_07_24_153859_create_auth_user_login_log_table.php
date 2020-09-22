<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Auth\UserLoginLog;

class CreateAuthUserLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = UserLoginLog::tableName();

        UserLoginLog::schemaConnection()->create($tableName, function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->comment('用户ID')->index();
            $table->json('languages')->comment('接受语言')->nullable();
            $table->string('device', 32)->comment('设备')->nullable();
            $table->string('platform', 32)->comment('平台')->nullable();
            $table->string('platform_version', 64)->comment('平台版本')->nullable();
            $table->string('browser', 32)->comment('浏览器')->nullable();
            $table->string('browser_version', 64)->comment('浏览器版本')->nullable();
            $table->string('robot', 64)->comment('机器人名称')->nullable();

            $table->ipAddress('ip')->comment('IP地址')->nullable();
            $table->string('iso_code', 16)->comment('国家代码')->nullable();
            $table->string('country', 64)->comment('国家')->nullable();
            $table->string('city', 64)->comment('城市')->nullable();
            $table->string('state', 16)->comment('省份代码')->nullable();
            $table->string('state_name', 64)->comment('省份')->nullable();
            $table->string('postal_code', 16)->comment('邮政编码')->nullable();
            $table->decimal('lat', 10, 6)->comment('纬度')->nullable();
            $table->decimal('lon', 10, 6)->comment('经度')->nullable();
            $table->string('timezone', 32)->comment('时区')->nullable();
            $table->string('continent', 16)->comment('州名')->nullable();
            $table->string('currency', 16)->comment('货币')->nullable();
            $table->boolean('default')->comment('默认值')->nullable();

            $table->unsignedInteger('created');
            $table->unsignedInteger('updated');
        });

        UserLoginLog::dbConnection()->statement("ALTER TABLE `{$tableName}` COMMENT '用户--登录日志'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        UserLoginLog::schemaConnection()->dropIfExists(UserLoginLog::tableName());
    }
}
