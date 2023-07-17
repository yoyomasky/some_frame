<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */

namespace core;


class Controller
{
    protected $middleware = [];

    /**
     * 获取中间件
     * @return array
     */
    public function getMiddleware(){
        return $this->middleware;
    }

    /**
     * 调用控制器方法  为了不限制参数
     * 所以方法设不设置$request 其实都无所谓
     * call_user_func_array 调用回调函数，并把一个数组参数作为回调函数的参数
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function callAction($method, $parameters){
        return call_user_func_array([$this, $method], $parameters);
    }
}