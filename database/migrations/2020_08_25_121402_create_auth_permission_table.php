<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Auth\Permission\Tree;
use App\Models\Admin\Auth\Permission\TreeUser;
use App\Models\Admin\Auth\Permission\TreeMenu;
use App\Models\Admin\Auth\User;

class CreateAuthPermissionTable extends Migration
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
        // 创建表结构
        Tree::schemaConnection()->create($tableNameTree, function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('sort')->comment('排序：值越小越靠前')->nullable()->index();
            $table->foreignId('parent_id')->comment('父ID')->default(0)->index();
            $table->string('path')->comment('依赖路径')->nullable()->index();
            $table->string('name', 64)->comment('节点名称');
            
            $table->unsignedInteger('created');
            $table->unsignedInteger('updated');
        });
        // 表名注释
        Tree::dbConnection()->statement("ALTER TABLE `{$tableNameTree}` COMMENT '权限--分级授权树'");
        
        /**
         * 授权树与用户关系表
         */
        $tableNameUser = User::tableName();
        $tableNameTreeUser = TreeUser::tableName();
        // 创建表结构
        TreeUser::schemaConnection()->create($tableNameTreeUser, function (Blueprint $table) use ($tableNameUser, $tableNameTree) {
            $table->foreignId('tree_id')->comment('授权树ID');
            $table->foreignId('user_id')->comment('用户ID')->index();
            
            // 关键约束
            $table->foreign('tree_id')->references('id')->on($tableNameTree)->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on($tableNameUser)->onDelete('cascade');
            
            // 联合主键
            $table->primary(['tree_id', 'user_id'], 'tree_has_users_primary');
        });
        // 表名注释
        TreeUser::dbConnection()->statement("ALTER TABLE `{$tableNameTreeUser}` COMMENT '权限--授权树与用户关系表'");
        
        /**
         * 授权树与菜单关系表
         */
        $tableNameTreeMenu = TreeMenu::tableName();
        // 创建表结构
        TreeUser::schemaConnection()->create($tableNameTreeMenu, function (Blueprint $table) use ($tableNameTree) {
            $table->foreignId('tree_id')->comment('授权树ID');
            $table->string('menu_route_name', 64)->comment('菜单路由名称')->index();
            
            // 关键约束
            $table->foreign('tree_id')->references('id')->on($tableNameTree)->onDelete('cascade');
            
            // 联合主键
            $table->primary(['tree_id', 'menu_route_name'], 'tree_has_menus_primary');
        });
        // 表名注释
        TreeUser::dbConnection()->statement("ALTER TABLE `{$tableNameTreeMenu}` COMMENT '权限--授权树与菜单关系表'");
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 分级授权树
        Tree::schemaConnection()->dropIfExists(Tree::tableName());
        // 授权树与用户关系表
        TreeUser::schemaConnection()->dropIfExists(TreeUser::tableName());
        // 授权树与菜单关系表
        TreeMenu::schemaConnection()->dropIfExists(TreeMenu::tableName());
    }
}
