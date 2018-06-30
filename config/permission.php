<?php

return [
    [
        'name' => '平台角色、用户管理',
        'groups' => [
            [
                'name' => '首页',
                'perms' => \App\Http\Controllers\HomeController::class,
            ],
            [
                'name' => '角色管理',
                'perms' => \App\Http\Controllers\Platform\PlatformRoleController::class,
            ],
            [
                'name' => '角色管理接口',
                'perms' => \App\Http\Controllers\Api\Platform\PlatformRoleApiController::class,
            ],
            [
                'name' => '用户管理',
                'perms' => \App\Http\Controllers\Platform\PlatformUserController::class,
            ],
            [
                'name' => '用户管理接口',
                'perms' => \App\Http\Controllers\Api\Platform\PlatformUserApiController::class,
            ],
        ],
    ],
    [
        'name' => '应用相关',
        'groups' => [
            [
                'name' => '应用管理',
                'perms' => \App\Http\Controllers\App\AppController::class,
            ],
            [
                'name' => '应用管理接口',
                'perms' => \App\Http\Controllers\Api\App\AppApiController::class,
            ],
            [
                'name' => '应用角色管理',
                'perms' => \App\Http\Controllers\App\AppRoleController::class,
            ],
            [
                'name' => '应用角色管理接口',
                'perms' => \App\Http\Controllers\Api\App\AppRoleApiController::class,
            ],
            [
                'name' => '应用用户管理',
                'perms' => \App\Http\Controllers\App\AppUserController::class,
            ],
            [
                'name' => '应用用户管理接口',
                'perms' => \App\Http\Controllers\Api\App\AppUserApiController::class,
            ],
        ],
    ],
];

/*
 * 配置示例
 */
/*
return [
    [
        'name' => '模块名称',
        'groups' => [
            [
                'name' => '分组名称',
                // 如果是控制器则对应于它的外部可访问的公共方法
                'perms' => \App\Http\Controllers\HomeController::class,
            ],
            [
                'name' => '分组名称',
                'perms' => [
                    'permOne' => '权限一',
                    'permTwo' => '权限二'
                ]
            ]
        ]
    ],
];
*/