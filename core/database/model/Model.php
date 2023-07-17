<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */
namespace core\database\model;
class Model
{
    // 绑定的数据库连接
    protected $connection;

    protected $table; // 表

    protected $paimaryKey; // 主键

    protected $timestamps = true; // 是否自动维护时间字段


    /*
    为什么要分开两个属性?
    $orginal 原的数据
    $attriubte 原数据的复制版 用户只能修改这个 !
    然后跟$original相比较 得出用户修改的数据字段
    */

    protected $original;
    protected $attribute;

    public function __construct(){
        // 给当前模型绑定一个数据库连接
        $this->connection = \App::getContainer()->get('db')->connection(
            $this->connection
        );
    }

    /**
     *  获取表名称
     *  如果没有表名称,就返回 模型(小写)
     * @return string
     */
    public function getTable(){
        if($this->table){
            return $this->table;
        }
        $class_name = get_class($this);
        $class_arr = explode('\\',$class_name);

        $table = lcfirst(end(
            $class_arr
        ));

        return  $table;
    }

    public function setOriginalValue($key, $val){
        if(!$this->original){
            $this->original = new \stdClass();
        }
        $this->original->$key = $val;
    }

    public function setAttribute($key, $val){
        if(! $this->attribute){
            $this->attribute = new \stdClass();
        }
        $this->attribute->$key = $val;
    }

    /**
     * 见最上面的说明
     * @param $key
     * @param $value
     */
    public function __set($key, $value){
        $this->setAttribute($key, $value);
    }

    /**
     *  属性 同步 original
     */
    public function syncOriginal(){
        $this->attribute = $this->original;
    }


    /**
     * 返回用户改过的数据
     * @return array
     * @example ['id' => 3,'user_id' => '3']
     */
    public function diff(){
        $diff = [];
        if( $this->attribute == $this->original){// 没改变
            return $diff;
        }
        foreach ($this->original as $origin_key => $origin_val){
            if($this->attribute->$origin_key != $origin_val){// 改变了
                $diff[$origin_key] = $this->attribute->$origin_key;
            }
        }
        return $diff;
    }



    public function __get($name){
        return $this->attribute->$name;
    }

    /**
     * 把调用 托管到 __call
     * 因此: User::where() 与 (new User)->where() 是一样的
     * PHP 5.3.0之后版本一般使用
     * 静态上下文中调用一个不可访问方法时
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args){
        return  (new static())->$method(...$args);
    }

    /**
     * 当调用类中不可调用的方法时会触发__call
     * 如果不懂可以参考 https://www.w3cschool.cn/php/php-__call.html 来了解~
     * 这里可以直接 new Builder 是因为他们都是同一个命名空间并且遵循PSR规范
     * 并且使用了autoload自动加载
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args){
        return (new Builder(
            $this->connection->newBuilder()
        ))
            ->setModel($this)
            ->$method(...$args);
    }
}