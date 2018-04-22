


公共初始化部分
公共函数库
公共加载类模块

配置 .ini M.RUN_MODE=debug / debug_level_base / debug_level_full  .  default debug_level_full
或者 $_REQUEST['_dug']==2

公共加载类模块 别名(alias)配置
    框架使用内部的加载函数 ， 不注册自动加载类 ， 兼容任何已有的加载规则如 composer psr4。
    class alias example:

根据不同入口加载不同初始化
注入初始化前执行部分
注入初始化后执行部分

directory
 - App 自定义
 - Core 核心文件
 - Module 公共加载的模块
 - Static 静态文件
 - Template 模板



 error code:
 50001 conf is null!  配置文件目录不存在！