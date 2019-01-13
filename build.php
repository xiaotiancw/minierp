<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 生成应用公共文件
    '__file__' => ['common.php'],

    // 定义demo模块的自动生成 （按照实际定义的文件名生成）
    'admin'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
        'controller' => [
            'Index', 'Login', 'User','Staff','Group', 'Auth', 'Log',
            'Record','Alarm','Assign'
            ],
        'model'      => ['User'],
        'view'       => [
            'index/index',
            'login/index',
            'user/index','user/create','user/edit',
            'staff/index','staff/create','staff/edit',
            'group/index','group/create','group/edit','group/detail',
            'auth/index','auth/create','auth/edit','auth/detail',
            'log/index',
            /*生产记录 */
            'record/index','record/create','record/edit',
            'record/expired',//已过交期列表
            'record/sha',//纱线处理
            'record/mtr',//备料处理
            'record/assign',//生产排单处理
            'record/lean',//领料处理
            'record/end',//领料处理
            /*加急处理通知 */
            'alarm/index',
            /*生产排单 */
            'assign/index',
        ],
    ],
    'home' => [
        '__file__' => ['common.php'],
        '__dir__' => ['behavior', 'controller', 'model', 'view'],
        'controller' => ['Index'],
        'model' => [],
        'view' => [
            'index/index'
        ],
    ],
    // 其他更多的模块定义
    'common' => [
        '__file__' => ['common.php'],
        '__dir__' => ['behavior', 'controller', 'model', 'view'],
        'controller' => ['BaseController'],
        'model' => [],
        'view' => [],
    ]
    
];
