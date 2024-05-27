<?php

namespace App\Enums;

enum TaskStatus: int
{
    case Open = 0;
    case InProgress = 1;
    case OnHold = 2;
    case Closed = 3;
    case Cancelled = 4;
}
