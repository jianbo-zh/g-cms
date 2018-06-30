<?php

namespace App\Http\Libraries;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * Class AuthUser
 * @package App\Http\Libraries
 */
class AuthUser implements AuthenticatableContract, AuthorizableContract, Arrayable
{
    use Authorizable;

    const FIELD_IDENTIFIER_NAME         = 'id';
    const FIELD_REMEMBER_TOKEN_NAME     = 'rememberToken';
    const FIELD_PASSWORD                = 'password';
    const FIELD_EMAIL                   = 'email';

    /**
     * @var array
     */
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = $this->attributes;

        unset($arr[self::FIELD_REMEMBER_TOKEN_NAME]);
        unset($arr[self::FIELD_PASSWORD]);

        return $arr;
    }

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->attributes[$this->getAuthIdentifierName()];
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return self::FIELD_IDENTIFIER_NAME;
    }

    /**
     * @return mixed|string
     */
    public function getAuthPassword()
    {
        return $this->attributes[self::FIELD_PASSWORD];
    }

    /**
     * @return mixed|string
     */
    public function getRememberToken()
    {
        return $this->attributes[$this->getRememberTokenName()];
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        return self::FIELD_REMEMBER_TOKEN_NAME;
    }

    /**
     * @param string $value
     */
    public function setRememberToken($value)
    {
        $this->attributes[$this->getRememberTokenName()] = $value;
    }

    /**
     * 检查是否是平台用户
     *
     * @return bool
     */
    public function checkIsPlatformUser()
    {
        return $this->attributes['userType'] === 'platform' ? true : false;
    }

    /**
     * 检查是否是应用用户
     *
     * @return bool
     */
    public function checkIsAppDeveloperOrManager()
    {
        if(
            $this->attributes['userType'] === 'app_developer' ||
            $this->attributes['userType'] === 'app_manager'
        ){
            return true;
        }

        return false;
    }

    /**
     * 检查是否是高级的应用用户（可以自己配置事物等权限）
     *
     * @return bool
     */
    public function checkIsAppDeveloper()
    {
        return ($this->attributes['userType'] === 'app_developer') ? true : false;
    }

    /**
     * 检查是否是普通的应用用户
     *
     * @return bool
     */
    public function checkIsAppManager()
    {
        return ($this->attributes['userType'] === 'app_manager') ? true : false;
    }

    /**
     * 检查是否是应用内容管理用户
     *
     * @return bool
     */
    public function checkIsAppContentUser()
    {
        return $this->attributes['userType'] === 'app_content' ? true : false;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->attributes[$name];
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->attributes[$name] = $value;
    }
}