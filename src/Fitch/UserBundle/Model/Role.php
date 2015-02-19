<?php

namespace Fitch\UserBundle\Model;

/**
 * Class Service
 *
 * Constants for Service Names
 *
 * @package Fitch\BotBundle
 */
final class Role
{
    const ROLE_USER = 'Basic Log-in';
    const ROLE_FULL_USER = 'Read Only (Full)';
    const ROLE_EDITOR = 'Editor (Limited)';
    const ROLE_FULL_EDITOR = 'Editor (Full)';
    const ROLE_SUPER_ADMIN = 'Full Access (inc. User Management)';

    public static function getRolesDictionary()
    {
        return [
            'ROLE_USER' => self::ROLE_USER,
            'ROLE_FULL_USER' => self::ROLE_FULL_USER,
            'ROLE_EDITOR' => self::ROLE_EDITOR,
            'ROLE_FULL_EDITOR' => self::ROLE_FULL_EDITOR,
            'ROLE_SUPER_ADMIN' => self::ROLE_SUPER_ADMIN
        ];
    }

    public static function toHuman($r) {
        return self::getRolesDictionary()[$r];
    }
}
