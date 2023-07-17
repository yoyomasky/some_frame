<?php
class IpCheck
{
    public static function handle($data, \Clourse $next)
    {
        if ("IP invalid") { // IP 不合法
            throw Exception("ip invalid");
        }
        return $next($data);
    }
}

class StatusManage
{
    public static function handle($data, \Clourse $next)
    {
        // exec 可以执行初始化状态的操作
        $ret = $next($data);
        // exec 可以执行保存状态信息的操作
        return $ret;
    }
}
$pipes = [
    IpCheck::class,
    StatusManage::class,
];

$data = "any things";
class Pipeline
{

    /**
     * The method to call on each pipe
     * @var string
     */
    protected $method = 'handle';

    /**
     * The object being passed throw the pipeline
     * @var mixed
     */
    protected $passable;

    /**
     * The array of class pipes
     * @var array
     */
    protected $pipes = [];

    /**
     * Set the object being sent through the pipeline
     *
     * @param $passable
     * @return $this
     */
    public function send($passable)
    {
        $this->passable = $passable;
        return $this;
    }

    /**
     * Set the method to call on the pipes
     * @param array $pipes
     * @return $this
     */
    public function through($pipes)
    {
        $this->pipes = $pipes;
        return $this;
    }

    /**
     * @param \Closure $destination
     * @return mixed
     */
    public function then(\Closure $destination)
    {
        $pipeline = array_reduce(array_reverse($this->pipes), $this->getSlice(), $destination);
        return $pipeline($this->passable);
    }


    /**
     * Get a Closure that represents a slice of the application onion
     * @return \Closure
     */
    protected function getSlice()
    {
        return function($stack, $pipe){
            return function ($request) use ($stack, $pipe) {
                return $pipe::{$this->method}($request, $stack);
            };
        };
    }

}




(new Pipeline())->send($data)->through($pipes)->then(function($data){ "执行其它逻辑";});