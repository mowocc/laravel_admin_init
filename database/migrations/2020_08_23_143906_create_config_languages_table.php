<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Admin\Config\Language;

class CreateConfigLanguagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = Language::tableName();

        Language::schemaConnection()->create($tableName, function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 64)->comment('语言名称');
            $table->string('lang', 16)->comment('语言code')->unique();

            $table->unsignedInteger('deleted')->nullable()->index();
            $table->unsignedInteger('created');
            $table->unsignedInteger('updated');
        });

        Language::dbConnection()->statement("ALTER TABLE `{$tableName}` COMMENT '配置--多语言本地化'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Language::schemaConnection()->dropIfExists(Language::tableName());
    }
}
