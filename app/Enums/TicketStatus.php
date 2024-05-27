<?php

namespace App\Enums;

enum TicketStatus: int
{
    case Assigned = 1;
    case InProgress = 2;
    case Resolved = 3;
    case Closed = 4;
    case Cancelled = 5;
}
