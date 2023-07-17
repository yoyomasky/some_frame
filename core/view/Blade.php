<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */

namespace core\view;

use duncan3dc\Laravel\BladeInstance;
class Blade implements ViewInterface
{
    protected $template;
    public function init(){
        $config = \APP::getContainer()->get('config')->get('view');//获取配置
        //设置视图方法 和 缓存路径 用法参考:duncan3dc/blade
        $this->template = new BladeInstance($config['view_path'],$config['cache_path']);
    }

    /**
     * 传递路径 和 参数
     * @param $path
     * @param array $params
     * @return mixed
     */
    public function render($path,$params = []){
        return  $this->template->render($path,$params);
    }
}