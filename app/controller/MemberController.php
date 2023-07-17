<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */
namespace App\controller;

use App\middleware\ControllerMiddleWare;
use core\Controller;
use core\request\RequestInterface;

/**
 * 集成控制器基类
 * Class MemberController
 * @package App\controller
 */
class MemberController extends Controller
{
    protected $middleware = [ // 这个控制器的中间件
        ControllerMiddleWare::class
    ];

    public function index(RequestInterface $request){
        return [
            'method' => $request->getMethod(),
            'url' =>  $request->getUri()
        ];
    }

    public function index2(){

    }
}