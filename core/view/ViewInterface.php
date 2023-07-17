<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */
namespace core\view;
interface ViewInterface
{
    /**
     * 初始化模版
     * @return mixed
     */
    public function init();

    /**
     * 解析模版
     * @param $path
     * @return mixed
     */
    function render($path);
}