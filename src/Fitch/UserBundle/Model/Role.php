<?php

namespace Fitch\UserBundle\Model;

/**
 * Class Role.
 *
 * Constants for Role Names and descriptions
 */
final class Role
{
    const ROLE_USER = 'Basic Log-in';
    const ROLE_SENSITIVE_DATA = 'Access Private/Sensitive data';
    const ROLE_EDITOR = 'Editor (Cannot add lookup values)';
    const ROLE_ADMIN = 'Full Access';
    const ROLE_SUPER_ADMIN = 'Full Access (inc. User Management)';

    const ROLE_CAN_ACCESS_SIDEBAR = 'Can access the sidebar';
    const ROLE_CAN_VIEW_TUTOR = 'Can view tutor details';
    const ROLE_CAN_EDIT_TUTOR = 'Can edit existing tutors';
    const ROLE_CAN_CREATE_TUTOR = 'Can create tutors';
    const ROLE_CAN_ACCESS_SENSITIVE_DATA = 'Can access sensitive tutor data';
    const ROLE_CAN_EDIT_LOOKUP_VALUES = 'Can edit existing lookup values';
    const ROLE_CAN_CREATE_LOOKUP_VALUES = 'Can create new lookup values';
    const ROLE_CAN_VIEW_SAVED_REPORTS = 'Can view saved reports';
    const ROLE_CAN_CREATE_AD_HOC_REPORTS = 'Can create ad-hoc reports';
    const ROLE_CAN_CREATE_SAVED_REPORTS = 'Can save reports';
    const ROLE_CAN_MANAGE_USERS = 'Can manage users';

    public static function getAssignableRolesDictionary()
    {
        return [
            'ROLE_SENSITIVE_DATA' => self::ROLE_SENSITIVE_DATA,
            'ROLE_EDITOR' => self::ROLE_EDITOR,
            'ROLE_ADMIN' => self::ROLE_ADMIN,
            'ROLE_SUPER_ADMIN' => self::ROLE_SUPER_ADMIN,
        ];
    }

    public static function getRolesDictionary()
    {
        return self::getAssignableRolesDictionary() + [
            'ROLE_USER' => self::ROLE_USER,
            'ROLE_CAN_ACCESS_SIDEBAR' => self::ROLE_CAN_ACCESS_SIDEBAR,
            'ROLE_CAN_VIEW_TUTOR' => self::ROLE_CAN_VIEW_TUTOR,
            'ROLE_CAN_EDIT_TUTOR' => self::ROLE_CAN_EDIT_TUTOR,
            'ROLE_CAN_CREATE_TUTOR' => self::ROLE_CAN_CREATE_TUTOR,
            'ROLE_CAN_ACCESS_SENSITIVE_DATA' => self::ROLE_CAN_ACCESS_SENSITIVE_DATA,
            'ROLE_CAN_EDIT_LOOKUP_VALUES' => self::ROLE_CAN_EDIT_LOOKUP_VALUES,
            'ROLE_CAN_CREATE_LOOKUP_VALUES' => self::ROLE_CAN_CREATE_LOOKUP_VALUES,
            'ROLE_CAN_VIEW_SAVED_REPORTS' => self::ROLE_CAN_VIEW_SAVED_REPORTS,
            'ROLE_CAN_CREATE_AD_HOC_REPORTS' => self::ROLE_CAN_CREATE_AD_HOC_REPORTS,
            'ROLE_CAN_CREATE_SAVED_REPORTS' => self::ROLE_CAN_CREATE_SAVED_REPORTS,
            'ROLE_CAN_MANAGE_USERS' => self::ROLE_CAN_MANAGE_USERS,
        ];
    }

    public static function toHuman($role)
    {
        return self::getRolesDictionary()[$role];
    }
}
