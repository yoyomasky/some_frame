<?php
/**
 * @author: somewhere
 * @since: 2021/6/24
 * @version: 1.0
 */
class Ioc
{
    public $binding = [];

    public function bind($abstract, $concrete)
    {
        if (!$concrete instanceof Closure) {
            $concrete = function ($ioc) use ($concrete) {
                return $ioc->build($concrete);
            };
        }

        $this->binding[$abstract]['concrete'] = $concrete;
    }

    public function make($abstract)
    {
        $concrete = $this->binding[$abstract]['concrete'];
        return $concrete($this);
    }

    public function build($concrete) {
        $reflector = new ReflectionClass($concrete);
        $constructor = $reflector->getConstructor();
        if(is_null($constructor)) {
            return $reflector->newInstance();
        }else {
            $dependencies = $constructor->getParameters();
            $instances = $this->getDependencies($dependencies);
            return $reflector->newInstanceArgs($instances);
        }
    }

    protected function getDependencies($paramters) {

        $dependencies = [];
        foreach ($paramters as $paramter) {
            $dependencies[] = $this->make($paramter->getClass()->name);
        }
        return $dependencies;
    }

}

interface log
{
    public function write();
}

// 文件记录日志
class FileLog implements Log
{
    public function write(){
        echo 'file log write...';
    }
}

// 数据库记录日志
class DatabaseLog implements Log
{
    public function write(){
        echo 'database log write...';
    }
}

class User
{
    protected $log;
    public function __construct(Log $log)
    {
        $this->log = $log;
    }
    public function login()
    {
        // 登录成功，记录登录日志
        echo 'login success...';
        $this->log->write();
    }
}

//实例化IoC容器
$ioc = new Ioc();
$ioc->bind('log','FileLog');
$ioc->bind('user','User');
$user = $ioc->make('user');
$user->login();
exit;