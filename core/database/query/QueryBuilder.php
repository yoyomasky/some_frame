<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */
namespace core\database\query;
use core\database\connection\Connection;

class QueryBuilder
{
    protected $connection;

    protected $grammar;

    public $binds;

    public $columns;

    public $distinct;

    public $form;

    public $union;

    public $tablePrefix;

    public $bindings = [
        'select' => [],
        'from' => [],
        'join' => [],
        'where' => [],
        'groupBy' => [],
        'having' => [],
        'order' => [],
        'union' => [],
        'unionOrder' => [],
    ];

    protected $operators = [
        '=','<','>','<=','>=','<>','!=','<=>','like','like binary','not like','ilike','&','|','^',
        '<<','>>','rlike','not rlike','regexp','not regexp','~','~*','!~','!~*','similar to','not similar to',
        'not ilike','~~*','!~~*'
    ];

    public function __construct(Connection $connection, $grammar){
        $this->connection = $connection; // 数据库连接
        $this->tablePrefix = $connection->tablePrefix;
        $this->grammar = $grammar; // 编译成sql的类
    }

    public function table(string $table,$tablePrefix = '', $as = null){
        $tablePrefix=strlen(trim($tablePrefix))>0?$tablePrefix:$this->tablePrefix;
        return $this->from($tablePrefix.$table,$as);
        // return (clone $this)->from($table,$as);
    }

    public function from($table,$as){
        $this->from = $as ? "{$table} as {$as}" : $table;
        return $this;
    }

    public function get($columns = ['*']){
        if(! is_array($columns)){
            $columns  = func_get_args();
        }

        $this->columns = $columns;
        $sql = $this->toSql();
        return $this->runSql($sql);
    }

    /**
     * 运行sql
     * @param $sql
     * @return mixed
     */
    public function runSql($sql){
        return $this->connection->executeSql(
            $sql,$this->getBinds()
        );
    }

    /**
     * Where 条件
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $joiner
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $joiner = 'and'){
        if ( is_array($column)){// 如果是 where(['id' => '2','name' => 'xxh']) 这种
            foreach ($column as $col => $value){
                $this->where($col,'=',$value);
            }
        }
        if(! in_array($operator,$this->operators)){ // 操作符不存在
            $value = $operator;
            $operator = '=';
        }

        $type = 'Basic';
        $this->wheres[] = compact(
            'type', 'column', 'operator', 'value', 'joiner'
        ); // 存到wheres变量

        $this->binds[] = $value;
        return $this;
    }

    /**
     *  orWhere
     * @param $column
     * @param null $operator
     * @param null $value
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null){
        return $this->where($column, $operator, $value ,'or');
    }

    /**
     * find
     * @param $id
     * @param string[] $columns
     * @param string $key
     * @return mixed
     */
    public function find($id,$columns = ['*'],$key = 'id'){
        return $this->where($key,$id)->get($columns);
    }

    /**
     * whereLike
     * @param $column
     * @param null $operator
     * @param null $value
     * @return $this
     */
    public function whereLike($column, $operator = null, $value = null){
        return $this->where($column, $operator, $value, 'like');
    }

    /**
     * @return mixed
     */
    public function toSql() {// 编译成sql
        return $this->grammar->compileSql($this);
    }

    /**
     * 绑定
     * @return mixed
     */
    public function getBinds(){// 绑定
        return $this->binds;
    }
}