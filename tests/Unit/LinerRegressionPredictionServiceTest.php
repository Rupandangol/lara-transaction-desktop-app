<?php

namespace Tests\Unit;

use App\Services\LinearRegressionPredictionService;
use Tests\TestCase;

class LinerRegressionPredictionServiceTest extends TestCase
{
    private LinearRegressionPredictionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LinearRegressionPredictionService;
    }

    public function test_returns_zero_when_data_set_is_empty()
    {
        $data = collect([]);
        $response = $this->service->predict($data);
        $this->assertSame(0, $response);
    }

    public function test_predicts_next_value_correctly()
    {
        $data = collect([
            (object) ['total_spent' => 10],
            (object) ['total_spent' => 20],
            (object) ['total_spent' => 30],
        ]);

        $result = $this->service->predict($data);

        $this->assertEquals(40, $result);
    }

    public function test_predicts_with_non_linear_data()
    {
        $data = collect([
            (object) ['total_spent' => 5],
            (object) ['total_spent' => 7],
            (object) ['total_spent' => 9],
        ]);

        $result = $this->service->predict($data);

        $this->assertEquals(11, round($result));
    }

    public function test_returns_zero_when_only_one_data_point()
    {
        $data = collect([
            (object) ['total_spent' => 50],
        ]);

        $result = $this->service->predict($data);

        $this->assertSame(0, $result);
    }
}
