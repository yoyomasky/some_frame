<?php
/**
 * @author: somewhere
 * @since: 2021/6/24
 * @version: 1.0
 */
class Container{

    protected $container = [];

    public function set($key,$instance)
    {
        $this->container[$key] = $instance;
    }

    public function get($key)
    {
        return $this->container[$key] ?? null;
    }
}

class User{
    public $str = 'hello world'.PHP_EOL;
}


$container = new Container();
$container->set('user',new User());
$container->set('user_str',
    $container->get('user')->str
);

echo $container->get('user_str');