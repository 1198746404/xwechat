<?php

use Illuminate\Support\Facades\Route;

Route::any('/','WeChatController@index');
Route::any('hello','WeChatController@hello');
Route::any('welcome',function(){
    return view('xwechat::welcome');
})->middleware('xwechat.check');

