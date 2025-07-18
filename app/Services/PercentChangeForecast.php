<?php

namespace App\Services;

class PercentChangeForecast
{
    const BASE_AMOUNT = 0;
    public function changePercent($current, $previous)
    {
        if ($previous->total_spent === self::BASE_AMOUNT) {
            $data['type'] = 'no-change';
            $data['changeAbs'] = self::BASE_AMOUNT;
            $data['change'] = self::BASE_AMOUNT;
            return $data;
        }
        $change = (($current->total_spent - $previous->total_spent) / abs($previous->total_spent)) * 100;
        if ($change > self::BASE_AMOUNT) {
            $data['type'] = 'increase';
        } else {
            $data['type'] = 'decrease';
        }
        $data['changeAbs'] = round($change, 2);
        $data['change'] = abs(round($change, 2));
        return $data;
    }

    public function changesPercent($monthlyExpenses)
    {
        $changeData = [];
        foreach ($monthlyExpenses as $index => $item) {
            if ($index === 0) {
                $changeData[$index] = [
                    'change' => 0,
                    'type' => 'no-change',
                    'changeAbs' => 0,
                    "month" => $item->month,
                    "year" => $item->year
                ];
                continue;
            }
            $changeData[$index] = $this->changePercent($item, $monthlyExpenses[$index - 1]);
            $changeData[$index]['month'] = $item->month;
            $changeData[$index]['year'] = $item->year;
        }
        return $changeData;
    }
}
