<?php
/**
 * @author: somewhere
 * @since: 2021/6/25
 * @version: 1.0
 */
function hello(){
    return "world";
}
if (! function_exists('response')) {
    function response()
    {
        return App::getContainer()->get('response');
    }
}

function app($name = null)
{
    if($name) // 如果选择了具体实例
        return App::getContainer()->get($name);
    return  App::getContainer();
}

function endView()
{
    $time = microtime(true) - FRAME_START_TIME;
    $memory = memory_get_usage() - FRAME_START_MEMORY;

    echo '<br/><br/><br/><br/><br/><hr/>';
    echo "运行时间: ". round($time * 1000,2) .'ms<br/>';
    echo "消耗内存: ". round($memory / 1024 / 1024,2) . 'm';
}

function config($key = null)
{
    if( $key){
        return App::getContainer()->get('config')->get($key) ;
    }
    return App::getContainer()->get('config');
}