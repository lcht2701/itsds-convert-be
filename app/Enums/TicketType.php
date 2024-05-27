<?php

namespace App\Enums;

enum TicketType: int
{
    case Online = 1;
    case Offline = 2;
    case Hybrid = 3;
}
