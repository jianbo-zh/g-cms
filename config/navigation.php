<?php

use App\Http\Controllers;

return [

    /*
     * 模块导航配置
     */

    'modules' => [
        '平台管理'     => ['url' => '/'],
    ],

    /*
     * 所有导航菜单定义
     */

    'sidebars' => [

        /*
         * 公共菜单，不同类型用户可共用
         */
        'common' => [

            'menu1' => [
                '应用管理' =>[
                    '应用角色' => [
                        'icon' => 'icon-people',
                        'action' => Controllers\App\AppController::class.'@indexAppRoles',
                        'url' => '/app/[APP_ID]/roles'
                    ],
                    '应用用户' => [
                        'icon' => 'icon-user',
                        'action' => Controllers\App\AppController::class.'@indexAppUsers',
                        'url' => '/app/[APP_ID]/users'
                    ],
                    '事物列表' => [
                        'icon' => 'icon-grid',
                        'action' => Controllers\Thing\ThingController::class.'@indexPlatformThings',
                        'url' => '/app/[APP_ID]/things'
                    ],
                ]
            ],

            'menu2' => [
                '事物管理' =>[
                    '结构定义' => [
                        'icon' => 'icon-people',
                        'action' => Controllers\App\AppController::class.'@indexAppRoles',
                        'url' => '/app/[APP_ID]/thing/[THING_ID]/fields'
                    ],
                    '状态定义' => [
                        'icon' => 'icon-user',
                        'action' => Controllers\App\AppController::class.'@indexAppUsers',
                        'url' => '/app/[APP_ID]/thing/[THING_ID]/states'
                    ],
                    '操作定义' => [
                        'icon' => 'icon-grid',
                        'action' => Controllers\Thing\ThingController::class.'@indexPlatformThings',
                        'url' => '/app/[APP_ID]/thing/[THING_ID]/operations'
                    ],
                    '消息管理' => [
                        'icon' => 'icon-grid',
                        'action' => Controllers\Thing\ThingController::class.'@indexPlatformThings',
                        'url' => '/app/[APP_ID]/thing/[THING_ID]/messages'
                    ],
                ],
            ],

            'menu3' => [
                '内容管理' => [
                    '_DYNAMIC_MENU_' => '_THING_LIST_'
                ]
            ],

            'menu4' => [
                '应用首页' => [
                    'icon' => 'icon-user',
                    'action' => Controllers\AppHomeController::class.'@index',
                    'url' => '/my-app'
                ]
            ],
        ],


        /*
         * 平台管理员菜单
         */

        'platform' => [
            'menu1' => [
                '系统管理' => [
                    "角色管理" => [
                        'icon' => 'icon-people',
                        'action' => Controllers\Platform\PlatformRoleController::class.'@indexPlatformRoles',
                        'url' => '/roles',
                    ],
                    '用户管理' => [
                        'icon' => 'icon-user',
                        'action' => Controllers\Platform\PlatformUserController::class.'@indexPlatformUsers',
                        'url' => '/users'
                    ],
                    '应用管理' => [
                        'icon' => 'icon-grid',
                        'action' => Controllers\App\AppController::class.'@indexApps',
                        'url' => '/apps'
                    ]
                ]
            ],
        ],


        /*
         * 开发者应用用户菜单
         */

        'developer' => [
            'menu1' => [
                '应用管理' => [
                    '应用列表' => [
                        'icon' => 'icon-grid',
                        'action' => Controllers\App\AppController::class.'@indexApps',
                        'url' => '/apps'
                    ]
                ]
            ],
        ],


        /*
         * 一般应用管理用户菜单
         */

        'normal' => [
            'menu1' => [
                '内容管理' => [
                    "角色管理" => [
                        'icon' => 'icon-people',
                        'action' => Controllers\Platform\PlatformRoleController::class.'@indexPlatformRoles',
                        'url' => '/roles',
                    ],
                    '用户管理' => [
                        'icon' => 'icon-user',
                        'action' => Controllers\Platform\PlatformUserController::class.'@indexPlatformUsers',
                        'url' => '/users'
                    ],
                    '_DYNAMIC_MENU_' => '_THING_LIST_'
                ]
            ],
        ],


        /*
         * 内容管理类型用户菜单定义
         */

        'content' => [

        ],
    ],

    /*
     * 当前节点面包屑路径配置及所属菜单
     */

    'accessNodes' => [
        Controllers\HomeController::class.'@index' => ['首页',
            ['platform'=>'platform.menu1']
        ],

        Controllers\AppHomeController::class.'@index' => ['应用首页',
            ['common'=>'common.menu4']
        ],

        Controllers\HomeController::class.'@editProfile' => ['个人中心',
            ['platform'=>'platform.menu1']
        ],

        Controllers\Platform\PlatformRoleController::class.'@indexPlatformRoles' => ['角色列表',
            ['platform'=>'platform.menu1']
        ],

        Controllers\Platform\PlatformUserController::class.'@indexPlatformUsers' => ['用户列表',
            ['platform'=>'platform.menu1']
        ],

        Controllers\App\AppController::class.'@indexApps' => ['应用列表',
            ['platform'=>'platform.menu1', 'developer'=>'developer.menu1']
        ],

        Controllers\App\AppController::class.'@manageApp' => ['应用管理',
            ['common'=>'common.menu1']
        ],

        Controllers\App\AppRoleController::class.'@indexAppRoles' => ['应用角色',
            ['common'=>'common.menu1']
        ],

        Controllers\App\AppUserController::class.'@indexAppUsers' => ['应用用户',
            ['common'=>'common.menu1']
        ],

        Controllers\Thing\ThingController::class.'@indexThings' => ['事物列表',
            ['common'=>'common.menu1']
        ],

        Controllers\Thing\ThingController::class.'@manageThing' => ['事物管理',
            ['common'=>'common.menu2']
        ],

        Controllers\Thing\FieldController::class.'@indexThingFields' => ['结构列表',
            ['common'=>'common.menu2']
        ],

        Controllers\Thing\StateController::class.'@indexThingStates' => ['状态列表',
            ['common'=>'common.menu2']
        ],

        Controllers\Thing\OperationController::class.'@indexThingOperations' => ['操作列表',
            ['common'=>'common.menu2']
        ],

        Controllers\Thing\MessageController::class.'@indexThingMessages' => ['消息列表',
            ['common'=>'common.menu2']
        ],

        Controllers\Content\ContentAppController::class.'@indexContentApp' => ['内容主页',
            ['normal'=>'normal.menu1', 'common'=>'common.menu3']
        ],

        Controllers\Content\ContentRoleController::class.'@indexContentRoles' => ['内容角色列表',
            ['normal'=>'normal.menu1']
        ],

        Controllers\Content\ContentUserController::class.'@indexContentUsers' => ['内容用户列表',
            ['normal'=>'normal.menu1']
        ],

        Controllers\Content\ContentThingController::class.'@indexContentThings' => ['事物内容列表',
            ['normal'=>'normal.menu1', 'common'=>'common.menu3']
        ],
    ]
];


