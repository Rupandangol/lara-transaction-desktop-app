<?php

namespace App\Actions\TransactionActions;

use App\Services\LinearRegressionPredictionService;
use App\Services\PercentChangeForecast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

final class GetTransactionsAggregates
{
    public const BASE_AMOUNT = 0;

    public const TEN_MIN_IN_SEC = 600;

    public function handle($query, $request, $nowYear)
    {
        $cacheKey = 'user_'.Auth::id().'_aggregates_'.md5(json_encode($request->only(['year', 'year_month', 'date', 'description'])));
        $aggregates = Cache::remember($cacheKey, self::TEN_MIN_IN_SEC, function () use ($query, $nowYear) {
            $total_transaction = (clone $query)->count();
            $total_spent = (clone $query)->sum('debit');
            $total_income = (clone $query)->sum('credit');
            $monthly_expenses =
                (clone $query)
                    ->selectRaw("strftime('%m', date_time) as month, strftime('%Y', date_time) as year, SUM(debit) as total_spent")
                    ->groupBy('month')
                    ->having('year', $nowYear)
                    ->orderBy('month')
                    ->get();

            if ($monthly_expenses->count() > 2) {
                $percentChangeForecast = (new PercentChangeForecast);
                $percent_change = $percentChangeForecast->changePercent($monthly_expenses->last() ?? self::BASE_AMOUNT, $monthly_expenses->reverse()->skip(1)->first() ?? self::BASE_AMOUNT);
                $percent_changes = collect($percentChangeForecast->changesPercent($monthly_expenses));
            }
            $top_expenses = (clone $query)->selectRaw('description , COUNT(*) as total,SUM(debit) as debit_sum')
                ->where('credit', self::BASE_AMOUNT)
                ->groupBy('description')
                ->orderByDesc('debit_sum')
                ->limit(5)
                ->get();
            $linear_regression_forecast = (new LinearRegressionPredictionService)->predict($monthly_expenses);
            $over_all_forecast = (clone $query)
                ->selectRaw("strftime('%m', date_time) as month, strftime('%Y', date_time) as year, SUM(debit) as total_spent")
                ->groupBy('year', 'month')
                ->orderByDesc('year')
                ->orderByDesc('month')
                ->get();
            $three_month_forecast = (clone $query)
                ->selectRaw("strftime('%m', date_time) as month, strftime('%Y', date_time) as year, SUM(debit) as total_spent")
                ->where('date_time', '>=', Carbon::now()->subMonths(3))
                ->groupBy('year', 'month')
                ->orderByDesc('year')
                ->orderByDesc('month')
                ->get();
            $over_all_time_based_spendings = (clone $query)
                ->selectRaw("strftime('%H', date_time) as hour, COUNT(*) as total, SUM(debit) as sum")
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();
            $tag_related_spendings = (clone $query)
                ->selectRaw('tag, COUNT(*) as total,SUM(debit) as debit_sum')
                ->groupBy('tag')
                ->orderByDesc('debit_sum')
                ->get();

            return [
                'total_transaction' => $total_transaction,
                'total_spent' => $total_spent,
                'total_income' => $total_income,
                'percent_change' => $percent_change ?? self::BASE_AMOUNT,
                'percent_changes' => $percent_changes ?? collect([]),
                'monthly_expenses' => $monthly_expenses,
                'top_expenses' => $top_expenses,
                'linear_regression_forecast' => round($linear_regression_forecast ?? 0),
                'over_all_forecast' => round($over_all_forecast->avg('total_spent')),
                'three_month_forecast' => round($three_month_forecast->avg('total_spent')),
                'over_all_time_based_spendings' => $over_all_time_based_spendings,
                'tag_related_spendings' => $tag_related_spendings,
            ];
        });

        return $aggregates;
    }
}
