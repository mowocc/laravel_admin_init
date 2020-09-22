<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 仅在本地环境中注册Telescope
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 国际化语言切换
        App::setLocale(Cookie::get('lang', config('app.locale')));
        
        // 数据库迁移字段默认字符串长度
        Schema::defaultStringLength(191);
        
        // Resource 禁用顶层资源的包裹
        JsonResource::withoutWrapping();
        
        // 自定义输出SQL语句
        $this->databaseBuilderToRawSql();
    }

    /**
     * 自定义输出SQL语句
     */
    protected function databaseBuilderToRawSql()
    {
        // Query Builder
        \Illuminate\Database\Query\Builder::macro('toRawSql', function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'{$binding}'", $sql, 1);
            }, $this->toSql());
        });
        
        // Eloquent Builder
        \Illuminate\Database\Eloquent\Builder::macro('toRawSql', function () {
            return $this->getQuery()->toRawSql();
        });
    }
}
