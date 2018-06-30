<?php

namespace App\Services\User\Model;

use App\Services\_Base\SimpleStateModel;
use Illuminate\Notifications\Notifiable;

/**
 * App\Services\User\Model\UserModel
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class UserModel extends SimpleStateModel
{
    use Notifiable;

    public const SUPER_USER_ID                  = 1;    // 平台超级管理员编号

    public const USER_TYPE_PLATFORM             = 'platform';
    public const USER_TYPE_APP_DEVELOPER        = 'app_developer';
    public const USER_TYPE_APP_MANAGER          = 'app_manager';
    public const USER_TYPE_APP_CONTENT          = 'app_content';

    protected const MAP_USER_TYPE = [
        self::USER_TYPE_PLATFORM                => '系统管理员',
        self::USER_TYPE_APP_DEVELOPER           => '应用开发者',
        self::USER_TYPE_APP_MANAGER             => '应用管理员',
        self::USER_TYPE_APP_CONTENT             => '应用运营员',
    ];

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_type', 'app_id', 'username', 'nickname', 'avatar', 'phone',
        'email', 'password', 'state', 'remember_token', 'api_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = ['user_type_text'];

    /**
     * 获取用户类型映射
     *
     * @return array
     */
    public static function getUserTypeMap()
    {
        return self::MAP_USER_TYPE;
    }

    /**
     * 获取用户类型文本
     *
     * @return mixed
     */
    public function getUserTypeTextAttribute()
    {
        $userTypeMap = self::getUserTypeMap();

        return $userTypeMap[$this->attributes['user_type']];
    }
}
