<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Config\Menu;

class CreateConfigMenusTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = Menu::tableName();
        
        Menu::schemaConnection()->create($tableName, function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('sort')->comment('排序：值越小越靠前')->nullable()->index();
            $table->foreignId('parent_id')->comment('父ID')->default(0)->index();
            $table->string('path')->comment('依赖路径')->nullable()->index();
            $table->string('name', 64)->comment('菜单名称');
            $table->string('icon', 64)->comment('font 图标名称')->nullable();
            $table->string('route_path', 64)->comment('前端路由');
            $table->string('route_name', 64)->comment('前端路由名称')->unique();
            
            $table->unsignedInteger('deleted')->nullable()->index();
            $table->unsignedInteger('created');
            $table->unsignedInteger('updated');
        });
        
        Menu::dbConnection()->statement("ALTER TABLE `{$tableName}` COMMENT '配置--菜单目录'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Menu::schemaConnection()->dropIfExists(Menu::tableName());
    }
}
