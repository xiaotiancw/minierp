<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应,绑定当前访问到admin模块
/**
 * 绑定后，我们的URL访问地址则变成：

http://serverName/index.php/控制器/操作/[参数名/参数值...]
访问的模块是index模块。

如果你的应用比较简单，模块和控制器都只有一个，那么可以在应用公共文件中绑定模块和控制器，如下：

// 绑定当前访问到index模块的index控制器
Container::get('app')->bind('index/index')->run()->send();
设置后，我们的URL访问地址则变成：

http://serverName/index.php/操作/[参数名/参数值...]
 */
Container::get('app')->bind('admin')->run()->send();


//+++++ 读取自动生成定义文件
$build = include __DIR__ . '/../build.php';
// 运行自动生成 必需消息app('build')
app('build')->run($build);
