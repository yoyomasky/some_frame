<?php
/**
 * @author: somewhere
 * @since: 2021/6/29
 * @version: 1.0
 */

namespace App\middleware;

use core\request\RequestInterface;
class ControllerMiddleWare
{
    public function handle(RequestInterface $request, \Closure $next){
        echo "<hr/>controller middleware<hr/>";
        return $next($request);
    }
}