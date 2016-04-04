<?php

namespace tests\codeception\math\unit;


use qubb\FixedPointMath\helpers\Rounder;

/**
 * work with bc math rounding
 *
 * @package tests\codeception\math\unit
 */
class RounderTest extends Codeception\TestCase\Test
{
    const CALCULATION_ADEQUACY = 8;

    public function setUp()
    {
        bcscale(self::CALCULATION_ADEQUACY);
    }

    /**
     * @return array
     */
    public function roundDataProvider()
    {
        /* [value, precision, floor hand result, round hand result, ceil hand result] */
        return MathDataProvider::roundingFixedPointCase();
    }

    /**
     * @test
     * @dataProvider roundDataProvider
     * @param $value
     * @param $precision
     * @param $floorResult
     * @param $roundResult
     * @param $ceilResult
     */
    public function testCeilForBcMath($value, $precision, $floorResult, $roundResult, $ceilResult)
    {
        $roundedFloatingValue = $this->ceilFloat($value, $precision);
        $roundedValue = Rounder::ceil($value, $precision);
        $this->assertEquals($roundedValue, $ceilResult, 'try to ceil ' . $value . ', precision ' . $precision
            . ', bcceil ' . $roundedValue . ', floating ceil ' . $roundedFloatingValue . ', hand ceil ' . $ceilResult);
    }

    /**
     * @test
     * @dataProvider roundDataProvider
     * @param $value
     * @param $precision
     * @param $floorResult
     * @param $roundResult
     * @param $ceilResult
     */
    public function testFloorForBcMath($value, $precision, $floorResult, $roundResult, $ceilResult)
    {
        $roundedFloatingValue = $this->floorFloat($value, $precision);
        $roundedValue = Rounder::floor($value, $precision);
        $this->assertEquals($roundedValue, $floorResult, 'try to floor ' . $value . ', precision ' . $precision
            . ', bcfloor ' . $roundedValue . ', floating floor ' . $roundedFloatingValue . ', hand floor ' . $floorResult);
    }

    /**
     * @test
     * @dataProvider roundDataProvider
     * @param $value
     * @param $precision
     * @param $floorResult
     * @param $roundResult
     * @param $ceilResult
     */
    public function testRoundForBcMath($value, $precision, $floorResult, $roundResult, $ceilResult)
    {
        $roundedFloatingValue = round($value, $precision);
        $roundedValue = Rounder::round($value, $precision);
        $this->assertEquals($roundedValue, $roundResult, 'try to round ' . $value . ', precision ' . $precision
            . ', bcround ' . $roundedValue . ', floating round ' . $roundedFloatingValue . ', hand round ' . $roundResult);
    }


    /**
     * @param $value
     * @param int $precision
     * @return float
     */
    private function ceilFloat($value, $precision = 0)
    {
        $precisionMultiplier = pow(10, $precision);
        $roundedAmount = ceil($value * $precisionMultiplier) / $precisionMultiplier;
        return $roundedAmount;
    }

    /**
     * @param $value
     * @param int $precision
     * @return mixed
     */
    private function floorFloat($value, $precision = 0)
    {
        $precisionMultiplier = pow(10, $precision);
        $roundedAmount = floor($value * $precisionMultiplier) / $precisionMultiplier;
        return $roundedAmount;
    }
}

