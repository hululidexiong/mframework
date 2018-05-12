# MFramework

概要：
 - 公共初始化部分
 - 公共函数库 ( 打算放弃 )
 - 配置文件加载
 - 别名设置
 - 公用加载类模块
 - 数据增删改查
 - Entity模型
 - 基于Entity的数据库结构维护
 - 基于Entity验证器（validate）
 - 模板渲染加载

开发环境：
php7.2
redis4.0
nginx1.4
mysql5.7

测试：
phpunit

配置 .ini M.RUN_MODE=debug / debug_level_base / debug_level_full  .  default debug_level_full
或者 $_REQUEST['_dug']==2

公共加载类模块 别名(alias)配置
    框架使用内部的加载函数 ， 不注册自动加载类 ， 兼容任何已有的加载规则如 composer psr4。
    class alias example:

根据不同入口加载不同初始化
注入初始化前执行部分
注入初始化后执行部分

###### directory tree
 - App 自定义
 - Core 核心文件
 - Module 公共加载的模块
    
 - Static 静态文件
 - Template 模板

process
 - 允许外部注入函数 （钩子）， 区分（初始化前 ， 初始化后 ， 路由方法执行前 ， 路由方法执行后 ，  最后输出 ....）

pseudo
array_push( C::Before , $Before1 );
for C::Before
    is_callable(C::Before[i])

 error code:
  - 50001 conf is null!  配置文件目录不存在！