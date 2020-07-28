<?php

namespace Xx\Wechat\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Xx\Wechat\Http\Middlewares\SWeChatCheck;

class WeChatServiceProvider extends ServiceProvider
{
    protected $commands = [
        \Xx\Wechat\Console\Commands\ControllerMakeCommand::class
    ];
    # 先执行,一般是配置 - 读取配置文件信息
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/xwechat.php','xwechat');# 将给定配置与现有配置合并
        $this->registerRouteMiddleware();
        $this->registerPublishing();# 配置文件注册
//        $this->commands($this->commands);
    }

    # 后执行 - 加载视图
    public function boot()
    {
        # 加载路由
        $this->registerRoutes();
        # 加载视图
        $this->loadViewsFrom(
            __DIR__.'/../Resources/views', 'xwechat'
        );
    }

    # 创建路由 - 注册路由
    public function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
        });
    }

    # 路由组信息
    private function routeConfiguration()
    {
        return [
            'namespace' => 'Xx\Wechat\Http\Controllers',
            'prefix' => 'xwechat',
        ];
    }


    # 中间件
    protected $routeMiddleware = [
        'xwechat.check' => SWeChatCheck::class
    ];
    protected $middlewareGroups = [];

    # 注册中间件
    protected function registerRouteMiddleware()
    {
        foreach ($this->routeMiddleware as $key => $middleware) {
            $this->app['router']->aliasMiddleware($key, $middleware);
        }

        # 可以省略  中间件组
        foreach ($this->middlewareGroups as $key => $middleware) {
            $this->app['router']->middlewareGroup($key, $middleware);
        }
    }

    # 配置文件命令
    public function registerPublishing()
    {
        // php artisan vendor:publish --provider="ShineYork\LaravelWechat\WeChatServiceProvider"
        if ($this->app->runningInConsole()) { // 是不是在控制台运行
            // 可以发布配置文件到指定目录: 获取配置文件            发布地址(配置文件名称)              分组
            $this->publishes([__DIR__.'/../Config/xwechat.php' => config_path('xwechat.php')], 'xwechat');
        }
    }
}
