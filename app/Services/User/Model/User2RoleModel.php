<?php

namespace App\Services\User\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Services\User\Model\User2RoleModel
 *
 * @mixin \Eloquent
 */
class User2RoleModel extends Model
{
    protected $table = 'user_user_2_role';
    protected $fillable = ['role_id', 'user_id'];

}
