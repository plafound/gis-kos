<?php

namespace App\Utils;

class Constant
{
    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    public const USER_STATUS_NOT_VERIFIED = 1;
    public const USER_STATUS_NOT_SUITABLE = 2;
    public const USER_STATUS_VERIFIED = 3;
}