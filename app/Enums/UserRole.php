<?php

namespace App\Enums;

enum UserRole: int
{
    case Customer = 0;
    case CompanyAdmin = 1;
    case Technician = 2;
    case Manager = 3;
    case Admin = 4;
}
