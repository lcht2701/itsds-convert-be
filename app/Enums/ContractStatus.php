<?php

namespace App\Enums;

enum ContractStatus: int
{
    case Pending = 0;
    case Active = 1;
    case InActive = 2;
    case Expired = 3;
}
