<?php

namespace App\Enum;

enum TagEnum: string
{
    case BILL_SHARING = 'bill_sharing';
    case FAMILY_EXPENSES = 'family_expenses';
    case FOOD_AND_DRINK = 'food_and_drink';
    case ENTERTAINMENT = 'entertainment';
    case UTILITIES = 'utilities';
    case TRAVEL = 'travel';
    case SHOPPING = 'shopping';
    case GROCERIES = 'groceries';
    case LEND = 'lend';
    case PERSONAL_USE = 'personal_use';
    case RIDE_SHARING = 'ride_sharing';
    case OTHERS = 'others';
}
