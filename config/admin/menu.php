<?php
/**
 * @filename menu.php
 * @author nish
 * @version 1.0
 *
 * Modified at : 2020-9-8 09:16:58
 *
 * 资源管理配置文件
 *
 */

//配置文件
return [
    'Index' => [
    	'title'	 => '首页',
    	'list'	 => [
    		[
    			'title'	=> '平台概况',
    			'list'	=> [
    			     ['title'=>'平台概况','m'=>'Index','a'=>'index', 'hide'=>true],
    			]
    		],
    	]
    ],

    'NameSearch' => [
        'title' => '名称管理',
        'list' => [
            'NameSearch' => [
                'title' =>  '列表',
                'list'  =>  [
                    ['title'=>'名称列表','m' => 'NameSearch', 'a'=>'index', 'other' =>['add_all']],

                    ['title'=>'批量添加', 'm'=>'NameSearch','a'=>'add', 'hide'=>true],
                    ['title'=>'删除', 'm'=>'NameSearch','a'=>'delete', 'hide'=>true],
                ]
            ],
        ],
    ],

    'Admin' => [
        'title' => '管理员管理',
        'list' => [
            'Admin' => [
                'title'	=>	'管理员管理',
                'list'	=>	[
                    ['title'=>'角色组管理', 'm'=>'Group','a'=>'index', 'other'=>['edit','add','edit_view','set']],
                    ['title'=>'创建角色组', 'm'=>'Group','a'=>'add', 'hide'=>true],
                    ['title'=>'修改角色组', 'm'=>'Group','a'=>'edit', 'hide'=>true],
                    ['title'=>'删除角色组', 'm'=>'Group','a'=>'delete', 'hide'=>true],
                    ['title'=>'设置字段', 'm'=>'Group','a'=>'set', 'hide'=>true],
                    ['title'=>'查看管理', 'm'=>'Group','a'=>'edit_view', 'hide'=>true],

                    ['title'=>'管理员列表', 'a'=>'index', 'other' => ['add', 'edit','edit_pwd']],
                    ['title'=>'添加管理员', 'a'=>'add', 'hide'=>true],
                    ['title'=>'修改管理员', 'a'=>'edit', 'hide'=>true],
                    ['title'=>'修改密码', 'a'=>'edit', 'hide'=>true],
                    ['title'=>'修改皮肤', 'a'=>'style', 'hide'=>true],
                    ['title'=>'操作状态', 'a'=>'status', 'hide'=>true],
                    ['title'=>'删除', 'a'=>'delete', 'hide'=>true],
                    ['title'=>'排序', 'a'=>'order', 'hide'=>true],
                ]
            ],
        ]
    ],

    'System' => [
        'title' => '系统管理',
        'list' => [
            'System' => [
                'title' => '相关配置',
                'list'  => [
                    ['title'=>'缓存管理', 'a'=>'cache'],
                ]
            ],
            'Log' => [
                'title' =>  '日志管理',
                'list'  =>  [
                    ['title'=>'日志列表','a'=>'index'],
                    ['title'=>'我的日志','a'=>'my'],
                    ['title'=>'删除日志','a'=>'delete','hide'=>true],
                ]
            ],
        ]
    ],
];