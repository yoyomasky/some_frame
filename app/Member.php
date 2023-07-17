<?php
/**
 * @author: somewhere
 * @since: 2021/6/24
 * @version: 1.0
 */

namespace App;


use core\database\model\Model;

class Member extends Model
{
    public function Run(){
        phpinfo();
    }
    public function sayPhp(){
        return "ID:{$this->mid} Name:{$this->m_name} say:PHP is the best language in the world~!";
    }
}