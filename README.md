# some_frame
手撸PHP现代化框架demo

```PHP
+---app
|   |   helpers.php // 函数
|   +---controller
|   |       MemberController.php // 示例控制器
|   +---middleware
|   |       ControllerMiddleWare.php
|   |       WebMiddleWare.php // web.php加载的中间件
|   \---models
|           Member.php // 示例模型
+---config
|       database.php // 数据库配置
|       view.php // 视图配置
+---core
|   |   Config.php  // 配置
|   |   Controller.php      // 基础控制器
|   |   PipleLine.php   // 管道
|   |   Response.php    // 响应
|   |   RouteCollection.php // 路由
|   +---database
|   |   |   Database.php 
|   |   +---connection
|   |   |       Connection.php
|   |   |       ConnectionInterface.php
|   |   |       MysqlConnection.php // mysql链接
|   |   +---model
|   |   |       Builder.php // 模型构造器
|   |   |       Model.php // 基础模型
|   |   \---query
|   |           Grammar.php
|   |           MysqlGrammar.php // 编译成sql语句
|   |           QueryBuilder.php // 查询构造器
|   +---log
|   |
|   +---request
|   |       PhpRequest.php  // 请求
|   |       RequestInterface.php 
|   \---view
|           Blade.php       // laravel模板引擎
|           View.php    // 视图适配器
|           ViewInterface.php 
+---public
|       index.php // 单一入口文件
+---routes
|       api.php 
|       web.php // 大部分功能可以在这里运行
+---app.php     // 框架要经过这个加载
```
