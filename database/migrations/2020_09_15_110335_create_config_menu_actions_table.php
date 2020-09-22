<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Config\MenuAction;

class CreateConfigMenuActionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = MenuAction::tableName();

        MenuAction::schemaConnection()->create($tableName, function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('menu_id')->comment('目录ID')->index();
            $table->string('menu_action', 128)->comment('页面功能')->unique();
            $table->string('action', 64)->comment('功能code');
            $table->string('name', 64)->comment('功能名称');
            $table->string('type', 16)->comment('类型[system,custom]');

            $table->unsignedInteger('created');
            $table->unsignedInteger('updated');
        });

        MenuAction::dbConnection()->statement("ALTER TABLE `{$tableName}` COMMENT '配置--页面功能'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        MenuAction::schemaConnection()->dropIfExists(MenuAction::tableName());
    }
}
