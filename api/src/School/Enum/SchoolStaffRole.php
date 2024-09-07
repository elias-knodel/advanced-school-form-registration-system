<?php

namespace App\School\Enum;

enum SchoolStaffRole: string
{
    case ADMIN = 'admin';
    case SECRETARY = 'secretary';
    case STAFF = 'staff';
}
