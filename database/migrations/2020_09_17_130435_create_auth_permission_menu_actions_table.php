<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Auth\Permission\Tree;
use App\Models\Admin\Auth\Permission\TreeMenuAction;

class CreateAuthPermissionMenuActionsTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 分级授权树
         */
        $tableNameTree = Tree::tableName();
        
        /**
         * 授权树与页面功能关系表
         */
        $tableNameTreeMenuAction = TreeMenuAction::tableName();
        // 创建表结构
        TreeMenuAction::schemaConnection()->create($tableNameTreeMenuAction, function (Blueprint $table) use ($tableNameTree) {
            $table->foreignId('tree_id')->comment('授权树ID');
            $table->string('menu_action', 128)->comment('页面功能')->index();
            
            // 关键约束
            $table->foreign('tree_id')->references('id')->on($tableNameTree)->onDelete('cascade');
            
            // 联合主键
            $table->primary(['tree_id', 'menu_action'], 'tree_has_menu_actions_primary');
        });
        // 表名注释
        TreeMenuAction::dbConnection()->statement("ALTER TABLE `{$tableNameTreeMenuAction}` COMMENT '权限--授权树与页面功能关系表'");
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 授权树与页面功能关系表
        TreeMenuAction::schemaConnection()->dropIfExists(TreeMenuAction::tableName());
    }
}
