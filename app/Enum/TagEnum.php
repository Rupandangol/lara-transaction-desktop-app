<?php

namespace App\Enum;

enum TagEnum: string
{
    case BILL_SHARING = 'bill_sharing';
    case FAMILY_EXPENSES = 'family_expenses';
    case GROCERIES = 'groceries';
    case LEND = 'lend';
    case PERSONAL_USE = 'personal_use';
    case RIDE_SHARING = 'ride_sharing';
    case OTHERS = 'others';
}
