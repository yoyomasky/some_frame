<?php
/**
 * @author: somewhere
 * @since: 2021/6/28
 * @version: 1.0
 */
$router->get('/hello',function(){
    return '你在访问Hello';
})->middleware(App\middleware\WebMiddleWare::class);

$router->get('/config',function(){
    echo App::getContainer()->get('config')->get('database.connections.mysql_one.driver').'<hr />';
    App::getContainer()->get('config')->set('database.connections.mysql_one.driver','mysql set');
    echo  App::getContainer()->get('config')->get('database.connections.mysql_one.driver');
});
$router->get('/db',function(){
    $id = 1;
    var_dump(
        App::getContainer()->get('db')->table('member')->where('mid',1)->get()
    );
});
$router->get('/model',function(){
    $users = App\Member::Where('mid',1)->get();
    foreach($users as $user){
        echo $user->sayPhp()."<hr >";
    }
});
$router->get('/controller','MemberController@index');

$router->get('view/blade',function(){
    $str = '这是Blade模版引擎';
    return App::getContainer()->get(core\view\ViewInterface::class)->render('blade.index',compact('str'));
});