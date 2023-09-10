<?php

namespace App\Service;

use App\Entity\User;

class DefaultUserService
{
    private static $defaultUser;

    private function __construct()
    {

    }

    public static function getDefaultUser(): User
    {
        if (!self::$defaultUser) {
            self::$defaultUser = new User();
            self::$defaultUser->setNickname('Gekota');
        }

        return self::$defaultUser;
    }
}
