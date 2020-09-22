<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Auth\User;

class CreateAuthUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = User::tableName();
        
        User::schemaConnection()->create($tableName, function (Blueprint $table) {
            $table->id();
            $table->boolean('is_super_admin')->comment('超级管理员：1是，0否')->default(0)->index();
            
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            $table->unsignedInteger('deleted')->nullable()->index();
            $table->unsignedInteger('created');
            $table->unsignedInteger('updated');
        });
        
        User::dbConnection()->statement("ALTER TABLE `{$tableName}` COMMENT '用户'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::schemaConnection()->dropIfExists(User::tableName());
    }
}
