<?php

namespace App\Enum;

enum StatusEnum: string
{
    case COMPLETE = 'COMPLETE';
    case PENDING = 'PENDING';
    case INCOMPLETE = 'INCOMPLETE';
}
