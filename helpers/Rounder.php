<?php

namespace qubb\FixedPointMath\helpers;

/**
 * Class Rounder
 * Используется для округления чисел в матимтеке с фиксированной точкой.
 * Библиотеки BC Math и GMP не содержат функций округления числе, данные числа можно будет округлить с использованием
 * функций данного класса, округление производится без приведения числа к типу float.
 *
 * Данная класс используется внутри функции BC Math, если она не была инициирована, то перед использованием данного
 * класса, необходимо ее инициировать. Сделать это можно с помощью Rounder::init($scale)
 * @see Rounder::init()
 *
 * @package qubb\FixedPointMath\helpers
 */
class Rounder
{
    /**
     * Задает количество чисел после десятичной точки по умолчанию для функций библиотеки BC Math
     * @link http://php.net/manual/ru/book.bc.php
     * @param int $scale
     */
    static function init($scale = 8) {
        bcscale($scale);
    }

    /**
     * Округление в верх числа с фиксированной точкой
     * @param string $value
     * @param int $precision
     * @return string
     */
    static public function ceil($value, $precision = 0)
    {
        if (self::isZeroRoundedPartOfValue($value, $precision)) {
            return $value;
        }

        if (!self::isNegativeValue($value)) {
            $roundedValue = self::bcCeilPositiveValue($value, $precision);
        }
        else {
            $roundedValue = self::bcCeilNegativeValue($value, $precision);
        }

        $roundedValue = self::resetRoundedPositions($roundedValue, $precision);

        return $roundedValue;
    }

    /**
     * Округление в низ числа с фиксированной точкой
     * @param string $value
     * @param int $precision
     * @return string
     */
    static public function floor($value, $precision = 0)
    {
        if (self::isZeroRoundedPartOfValue($value, $precision)) {
            return $value;
        }

        if (!self::isNegativeValue($value)) {
            $roundedValue = self::bcFloorPositiveValue($value, $precision);
        }
        else {
            $roundedValue = self::bcFloorNegativeValue($value, $precision);
        }

        $roundedValue = self::resetRoundedPositions($roundedValue, $precision);

        return $roundedValue;
    }

    /**
     * Математическое округление числа с фиксированной точкой
     * @param string $value
     * @param int $precision
     * @return string
     */
    static public function round($value, $precision = 0)
    {
        if (self::isZeroRoundedPartOfValue($value, $precision)) {
            return $value;
        }

        if (!self::isNegativeValue($value)) {
            $roundedValue = self::bcRoundPositiveValue($value, $precision);
        }
        else {
            $roundedValue = self::bcRoundNegativeValue($value, $precision);
        }

        $roundedValue = self::resetRoundedPositions($roundedValue, $precision);

        return $roundedValue;
    }

    /**
     * Отвечает на вопрос, равна ли округляемая часть нулю
     * @param string $value
     * @param int $precision
     * @return bool
     */
    static private function isZeroRoundedPartOfValue($value, $precision)
    {
        $precisionPosition = self::getPrecisionPosition($value, $precision);
        $roundedPart = self::getRoundedPartOfValue($value, $precisionPosition);
        return 0 == $roundedPart;
    }

    /**
     * Возвращает позицию с которой будет начинатся округление
     * @param string $value
     * @param int $precision
     * @return int
     */
    static private function getPrecisionPosition($value, $precision)
    {
        if (($delimiter = strpos($value, '.')) === false) {
            $delimiter = strlen($value);
        }
        else {
            $delimiter++;
        }
        $precisionPosition = $delimiter + $precision;
        return $precisionPosition;
    }

    /**
     * Возращает округляемую часть числа
     * @param string $value
     * @param int $precisionPosition
     * @return string
     */
    static private function getRoundedPartOfValue($value, $precisionPosition)
    {
        if (strlen($value) > $precisionPosition) {
            $lostPart = substr($value, $precisionPosition);
        }
        else {
            $lostPart = '0';
        }
        return $lostPart;
    }

    /**
     * Отвечает на вопрос, является ли число отрицательным
     * @param string $value
     * @return bool
     */
    static private function isNegativeValue($value)
    {
        return $value[0] == '-';
    }

    /**
     * @param string $value
     * @param int $precision
     * @return string
     */
    static private function bcCeilPositiveValue($value, $precision)
    {
        if ($precision == 0) {
            return bcadd($value, '1', $precision);
        }
        elseif ($precision > 0) {
            return bcadd($value, '0.' . str_repeat('0', $precision - 1) . '1', $precision);
        }
        else {
            return bcadd($value, '1' . str_repeat('0', -$precision), $precision);
        }
    }

    /**
     * @param string $value
     * @param int $precision
     * @return string
     */
    static private function bcCeilNegativeValue($value, $precision)
    {
        return bcsub($value, 0, $precision);
    }

    /**
     * @param string $value
     * @param int $precision
     * @return string
     */
    static private function bcFloorPositiveValue($value, $precision)
    {
        return bcadd($value, 0, $precision);
    }

    /**
     * @param string $value
     * @param int $precision
     * @return string
     */
    static private function bcFloorNegativeValue($value, $precision)
    {
        if ($precision == 0) {
            return bcsub($value, '1', $precision);
        }
        elseif ($precision > 0) {
            return bcsub($value, '0.' . str_repeat('0', $precision - 1) . '1', $precision);
        }
        else {
            return bcsub($value, '1' . str_repeat('0', -$precision), $precision);
        }
    }

    /**
     * @param string $value
     * @param int $precision
     * @return string
     */
    static private function bcRoundPositiveValue($value, $precision)
    {
        if ($precision == 0) {
            return bcadd($value, '0.5', $precision);
        }
        elseif ($precision > 0) {
            return bcadd($value, '0.' . str_repeat('0', $precision) . '5', $precision);
        }
        else {
            return bcadd($value, '5' . str_repeat('0', -$precision - 1), $precision);
        }
    }

    /**
     * @param string $value
     * @param int $precision
     * @return string
     */
    static private function bcRoundNegativeValue($value, $precision)
    {
        if ($precision == 0) {
            return bcsub($value, '0.5', $precision);
        }
        elseif ($precision > 0) {
            return bcsub($value, '0.' . str_repeat('0', $precision) . '5', $precision);
        }
        else {
            return bcsub($value, '5' . str_repeat('0', -$precision - 1), $precision);
        }
    }

    /**
     * Сбрасывает целочисленные разряды попавшие под округление, при precision < 0
     * @param string $roundedValue
     * @param int $precision
     * @return string
     */
    static private function resetRoundedPositions($roundedValue, $precision)
    {
        $roundedValuePrecisionPosition = self::getPrecisionPosition($roundedValue, $precision);
        if ($precision < 0) {
            for ($i = $roundedValuePrecisionPosition; $i < strlen($roundedValue); $i++) {
                $roundedValue[$i] = 0;
            }
        }
        return $roundedValue;
    }
}