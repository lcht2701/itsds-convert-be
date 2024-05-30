<?php

namespace App\Enums;

enum TicketStatus: int
{
    case Assigned = 0;
    case InProgress = 1;
    case Resolved = 2;
    case Closed = 3;
    case Cancelled = 4;
}
