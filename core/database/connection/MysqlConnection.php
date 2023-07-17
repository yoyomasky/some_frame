<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */

namespace core\database\connection;

use core\database\query\MysqlGrammar;
use core\database\query\QueryBuilder;
/**
 * 继承基础类
 * Class MysqlConnection
 * @package core\database\connection
 */
class MysqlConnection extends Connection
{
    protected static $connection;

    public function getConnection(){
        return self::$connection;
    }

    /**
     * 执行SQL
     * @param $sql
     * @param array $bindings
     * @param bool $useReadPdo
     * @return mixed
     */
    public function executeSql($sql, $bindings = [], $useReadPdo = true){
        $statement = $this->pdo;
        $sth = $statement->prepare($sql);
        try {
            $sth->execute( $bindings);
            return  $sth->fetchAll();
        } catch (\PDOException $exception){
            echo ($exception->getMessage());
        }
    }

    /**
     * 调用不存在的方法 调用一个新的查询构造器
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters){
        // 返回QueryBuilder类
        return $this->newBuilder()->$method(...$parameters);
    }
    /**
     * 创建新的查询器
     * @return QueryBuilder
     */
    public function newBuilder(){
        return  new QueryBuilder($this, new MysqlGrammar());
    }
}