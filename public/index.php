<?php
/**
 * @author: somewhere
 * @since: 2021/6/24
 * @version: 1.0
 */
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app.php';

//绑定request
App::getContainer()->bind(core\request\RequestInterface::class,function(){
   return core\request\PhpRequest::create(
       $_SERVER['REQUEST_URI'],$_SERVER['REQUEST_METHOD'],$_SERVER
   );
});

//App::getContainer()->get('response')->setContent(
//    App::getContainer()->get(core\request\RequestInterface::class)->getMethod()
//)->send();

App::getContainer()->get('response')->setContent(
    App::getContainer()->get('router')->dispatch(
        App::getContainer()->get(core\request\RequestInterface::class)
    )
)->send();