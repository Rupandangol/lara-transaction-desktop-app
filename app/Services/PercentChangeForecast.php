<?php

namespace App\Services;

class PercentChangeForecast
{
    const BASE_AMOUNT = 0;
    public function changePercent($current, $previous)
    {
        if ($previous === self::BASE_AMOUNT) {
            $data['type'] = 'no-change';
            $data['change'] = self::BASE_AMOUNT;
            return $data;
        }
        $change = (($current->total_spent - $previous->total_spent) / abs($previous->total_spent)) * 100;
        if ($change > self::BASE_AMOUNT) {
            $data['type'] = 'increase';
        } else {
            $data['type'] = 'decrease';
        }
        $data['change'] = abs(round($change, 2));
        return $data;
    }
}
