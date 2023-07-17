<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */
namespace core\database\connection;


/**
 * 链接的基础类
 * Class Connection
 * @package core\database\connection
 */
class Connection
{
    protected $pdo;
    public $tablePrefix;
    protected $config;
    public function __construct($pdo, $config){
        $this->pdo = $pdo;
        $this->tablePrefix = $config['prefix'];
        $this->config = $config;
    }


}