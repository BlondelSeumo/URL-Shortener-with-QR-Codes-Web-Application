<?php

/*
 * The MIT License
 *
 * Copyright (c) 2024 "YooMoney", NBСO LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace YooKassa\Model\Receipt;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;

/**
 * Class MarkQuantity
 *
 * Дробное количество маркированного товара
 *
 * @package YooKassa
 *
 * @property string $numerator Числитель — количество продаваемых товаров из одной потребительской упаковки (тег в 54 ФЗ — 1293)
 * @property string $denominator Знаменатель — общее количество товаров в потребительской упаковке (тег в 54 ФЗ — 1294)
 */
class MarkQuantity extends AbstractObject
{
    /** @var int Минимальное значение */
    const MIN_VALUE = 1;

    /**
     * @var integer Числитель — количество продаваемых товаров из одной потребительской упаковки (тег в 54 ФЗ — 1293). Не может превышать denominator
     */
    private $_numerator;

    /**
     * @var integer Знаменатель — общее количество товаров в потребительской упаковке (тег в 54 ФЗ — 1294)
     */
    private $_denominator;

    /**
     * Возвращает числитель
     * @return integer Числитель
     */
    public function getNumerator()
    {
        return $this->_numerator;
    }

    /**
     * Устанавливает числитель
     * @param integer $value Числитель
     */
    public function setNumerator($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty numerator value', 0, 'MarkQuantity.numerator');
        }
        if (!is_numeric($value)) { // todo: ??
            throw new InvalidPropertyValueTypeException('Invalid numerator value type', 0, 'MarkQuantity.numerator', $value);
        }
        if ((int)$value < self::MIN_VALUE) {
            throw new InvalidPropertyValueException('Invalid numerator value: "' . $value . '"', 0, 'MarkQuantity.numerator', $value);
        }

        $this->_numerator = (int)$value;

        return $this;
    }

    /**
     * Возвращает знаменатель
     * @return integer Знаменатель
     */
    public function getDenominator()
    {
        return $this->_denominator;
    }

    /**
     * Устанавливает знаменатель
     * @param integer $value Знаменатель
     */
    public function setDenominator($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty denominator value', 0, 'MarkQuantity.denominator');
        }
        if (!is_numeric($value)) { // todo: ??
            throw new InvalidPropertyValueTypeException('Invalid denominator value type', 0, 'MarkQuantity.denominator', $value);
        }
        if ((int)$value < self::MIN_VALUE) {
            throw new InvalidPropertyValueException('Invalid denominator value: "' . $value . '"', 0, 'MarkQuantity.denominator', $value);
        }

        $this->_denominator = (int)$value;

        return $this;
    }
}
