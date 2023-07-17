<?php
/**
 * @author: somewhere
 * @since: 2021/6/28
 * @version: 1.0
 */

namespace App\middleware;


class WebMiddleWare
{
    public function handle($request,\Closure $next){
        echo "web middleware------------>";
        return $next($request);
    }
}