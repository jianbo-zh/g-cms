<?php

namespace App\Services\User\Model;

use App\Services\_Base\SimpleStateModel;

/**
 * App\Services\User\Model\RoleModel
 *
 * @mixin \Eloquent
 */
class RoleModel extends SimpleStateModel
{
    protected $table = 'user_roles';
    protected $fillable = ['app_id', 'name', 'description', 'perms', 'state'];

    /**
     * 应该被转换成原生类型的属性。
     *
     * @var array
     */
    protected $casts = [
        'perms' => 'array',
    ];

}
