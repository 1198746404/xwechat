<?php

namespace Xx\Wechat\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand as Command;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

/*
因为在框架中,就是laravel自带的 make:controller已经提供了项目的基本功能

自定功能只是对于其原有的功能进行一步改变和补充 -> 就可以采用继承的思想 ; 重写方法
 */
class ControllerMakeCommand extends Command
{
    // protected $signature = 'swechat-make:controller';
    protected $name = 'xwechat-make:controller';
    protected $description = '这是组件中的创建Controller的命令';

    protected $namespace = "Xx\Wechat\Http\Controllers";

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');
        return $this->namespace.'\\'.$name;
    }
    protected function getPath($name)
    {
        // $this->rootNamespace() => App => ShineYork\LaravelWechat
//        var_dump($name);#  "Xx\Wechat\Http\Controllers\xwechatTest"
//        var_dump($this->rootNamespace());# "Xx\Wechat\
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        # var_dump($name);# "Http\Controllers\xwechatTest"
        # var_dump(app()->basePath());# D:\harry\tools\php\phpstudy_pro\WWW\Project\blog
        # var_dump(app()->basePath().'\\vendor\xx\wechat\src\\'.str_replace('\\', '/', $name).'.php');# D:\harry\tools\php\phpstudy_pro\WWW\Project\blog\vendor\xx\wechat\src\Http/Controllers/xwechatTest.php
        return app()->basePath().'\\vendor\xx\wechat\src\\'.str_replace('\\', '/', $name).'.php';
    }
    public function rootNamespace()
    {
        return "Xx\Wechat\\";# 确定命名空间 一面和路径中的 与 命名空间同名部分重复
    }
}
