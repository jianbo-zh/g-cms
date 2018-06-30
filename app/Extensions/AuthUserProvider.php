<?php

namespace App\Extensions;

use App\Http\Libraries\AuthUser;
use App\Services\User\Service\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;


class AuthUserProvider implements UserProvider
{
    /**
     * @var static
     */
    protected $userService;

    public function __construct()
    {
        $this->userService = UserService::instance();
    }

    /**
     * @param array $credentials
     * @return AuthUser|Authenticatable|null
     * @throws \App\Services\_Base\Exception
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = $this->userService->getUserByCredentials('123', $credentials);

        return $user ? $this->getAuthUser($user) : null;
    }

    /**
     * @param mixed $identifier
     * @return AuthUser|Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $user = $this->userService->getUser('123', $identifier);

        return $this->getAuthUser($user);
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return AuthUser|Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = $this->userService->getUser('123', $identifier);

        return ($user['rememberToken'] == $token) ? $this->getAuthUser($user) : null;
    }

    /**
     * @param Authenticatable $user
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $this->userService->updateUserRememberToken('123', $user->getAuthIdentifier(), $token);
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $result = $this->userService->checkUserPassword('123', $credentials['password'],
            $user->getAuthPassword());

        return $result;
    }

    /**
     * @param array $user
     * @return AuthUser|null
     */
    protected function getAuthUser(array $user)
    {
        if (! is_null($user)) {
            return new AuthUser((array) $user);
        }
        return null;
    }

}