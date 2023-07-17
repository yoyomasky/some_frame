<?php
/**
 * @author: somewhere
 * @since: 2021/6/28
 * @version: 1.0
 */

namespace core;

use core\request\RequestInterface;
class RouteCollection
{
    protected $routes = []; // 所有路由存放
    protected $route_index = 0;  // 当前访问的路由
    public $currGroup = [];  //
    public function getRoutes(){
        return $this->routes;
    }

    /**
     * group的实现主要的是这个$this 这个$this将当前状态传递到了闭包
     * @param array $attributes
     * @param \Closure $callback
     */
    public function group($attributes=[],\Closure $callback){
        $this->currGroup[] = $attributes;
        call_user_func($callback,$this);//执行用户回调
        //$callback($this);
        array_pop($this->currGroup);
    }

    /**
     * 主要是给$uri增加 /
     * 例如:GETUSER 改写成 GET/USER
     * @param $uri
     * @return mixed|string
     */
    protected function addSlash(&$uri){
        return $uri[0] == '/'?:$uri = '/'.$uri;
    }

    /**
     * 主要就是 增加路由
     * @param $method 请求方式
     * @param $uri 路由
     * @param $uses 闭包 或 controller@方法
     */
    public function addRoute($method,$uri,$uses){
        $prefix = '';//前缀
        $middleware=[];//中间件
        $namespace = '';;//命名空间
        $this->addSlash($uri);
        foreach($this->currGroup as $group){
            $prefix .= $group['prefix'] ?? false;
            if($prefix){//如果有前缀的话
                $this->addSlash($prefix);//用前缀再搞一次
            }
            $middleware = $group['middleware']??[];//合并组中间件
            $namespace = $group['namespace']??'';//拼接组的命名空间
        }
        $method = strtoupper($method);//请求方式 转大写
        $uri = $prefix.$uri;
        $this->route_index = $method . $uri; // 路由索引
        $this->routes[$this->route_index] = [ // 路由存储结构  用 GET/USER   这种方式做索引 一次性就找到了
            'method' => $method,  // 请求类型
            'uri' => $uri,  // 请求url
            'action' => [
                'uses' => $uses,
                'middleware' => $middleware,
                'namespace' => $namespace
            ]
        ];
    }

    public function get($uri,$uses){
        $this->addRoute('get',$uri,$uses);
        return $this;
    }
    public function post($uri,$uses){
        $this->addRoute('post',$uri,$uses);
        return $this;
    }
    public function put($uri,$uess){}
    public function delete($uri,$uses){}

    public function middleware($class){
        $this->routes[$this->route_index]['action']['middleware'][] = $class;
        return $this;
    }
    // 获取当前访问的路由
    public function getCurrRoute(){
        $routes = $this->getRoutes();
        $route_index = $this->route_index;

        if( !isset($routes[$route_index])){
            $route_index .= '/';
        }
        return  $routes[ $route_index];
    }

    /**
     * 根据request执行路由
     * @param RequestInterface $request
     * @return int
     */
    public function dispatch(RequestInterface $request){

        $method = $request->getMethod();
        $uri = $request->getUri();
        $this->route_index = $method . $uri;

        $route = $this->getCurrRoute();
        if(! $route){// 找不到路由
            return 404;
        }
        $middleware = $route['action']['middleware'] ?? [];
        $routerDispatch = $route['action']['uses'];
        if(!$route['action']['uses'] instanceof \Closure){//判断是否是闭包函数 如果不是 那就是控制器了
            $action = $route['action'];
            $uses = explode('@',$route['action']['uses']);
            $controller = $action['namespace'].'\\'.$uses[0];//拿到控制器
            $method = $uses[1];//要执行的方法
            $controllerInstance = new $controller;//实例化控制器
            $middleware = array_merge($middleware,$controllerInstance->getMiddleware());//合并控制器中间件
            $routerDispatch = function($request)use($route, $controllerInstance, $method){
                return $controllerInstance->callAction($method,[$request]);
            };
        }
        //return $routerDispatch();
        return \App::getContainer()->get('pipeline')->create()->setClass(
            $middleware
        )->run($routerDispatch)($request);
    }
}