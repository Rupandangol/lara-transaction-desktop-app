<?php

namespace App\Services;

class LinearRegressionPredictionService
{
    const NUMERATOR = 0;
    const DENOMINATOR = 0;
    
    public function predict($data)
    {
        // linear equation
        // y=b0 + b1x;
        // x=independent variable
        // y=output
        // b1=slope
        // b0= intercept
        if ($data->isEmpty()) {
            return 0;
        }
        $x = $data->map(function ($item, $index) {
            return $index + 1;
        })->toArray();
        $y = $data->map(function ($item) {
            return $item->total_spent;
        })->toArray();
        $n = count($x);
        $x_mean = array_sum($x) / $n;
        $y_mean = array_sum($y) / $n;

        $numerator = self::NUMERATOR;
        $denominator = self::DENOMINATOR;

        for ($i = 0; $i < $n; $i++) {
            $numerator += ($x[$i] - $x_mean) * ($y[$i] - $y_mean);
            $denominator += ($x[$i] - $x_mean) ** 2;
        }
        if ($denominator == 0) {
            return 0;
        }

        $b1 = $numerator / $denominator;

        $b0 = $y_mean - $b1 * $x_mean;
        $next_x = $n + 1;
        $predicted_y = $b0 + ($b1 * $next_x);

        return abs($predicted_y);
    }
}
