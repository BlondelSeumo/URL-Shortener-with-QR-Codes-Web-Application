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

use YooKassa\Common\AbstractEnum;

/**
 * Мера количества предмета расчета передается в массиве `items`, в параметре `measure`.
 * Параметр нужно передавать, начиная с ФФД 1.2.
 */
class ReceiptItemMeasure extends AbstractEnum
{
    /** Штука, единица товара */
    const PIECE = 'piece';
    /** Грамм */
    const GRAM = 'gram';
    /** Килограмм */
    const KILOGRAM = 'kilogram';
    /** Тонна */
    const TON = 'ton';
    /** Сантиметр */
    const CENTIMETER = 'centimeter';
    /** Дециметр */
    const DECIMETER = 'decimeter';
    /** Метр */
    const METER = 'meter';
    /** Квадратный сантиметр */
    const SQUARE_CENTIMETER = 'square_centimeter';
    /** Квадратный дециметр */
    const SQUARE_DECIMETER = 'square_decimeter';
    /** Квадратный метр */
    const SQUARE_METER = 'square_meter';
    /** Миллилитр */
    const MILLILITER = 'milliliter';
    /** Литр */
    const LITER = 'liter';
    /** Кубический метр */
    const CUBIC_METER = 'cubic_meter';
    /** Килловат-час */
    const KILOWATT_HOUR = 'kilowatt_hour';
    /** Гигакалория */
    const GIGACALORIE = 'gigacalorie';
    /** Сутки */
    const DAY = 'day';
    /** Час */
    const HOUR = 'hour';
    /** Минута */
    const MINUTE = 'minute';
    /** Секунда */
    const SECOND = 'second';
    /** Килобайт */
    const KILOBYTE = 'kilobyte';
    /** Мегабайт */
    const MEGABYTE = 'megabyte';
    /** Гигабайт */
    const GIGABYTE = 'gigabyte';
    /** Терабайт */
    const TERABYTE = 'terabyte';
    /** Другое */
    const ANOTHER = 'another';

    protected static $validValues = array(
        self::PIECE             => true,
        self::GRAM              => true,
        self::KILOGRAM          => true,
        self::TON               => true,
        self::CENTIMETER        => true,
        self::DECIMETER         => true,
        self::METER             => true,
        self::SQUARE_CENTIMETER => true,
        self::SQUARE_DECIMETER  => true,
        self::SQUARE_METER      => true,
        self::MILLILITER        => true,
        self::LITER             => true,
        self::CUBIC_METER       => true,
        self::KILOWATT_HOUR     => true,
        self::GIGACALORIE       => true,
        self::DAY               => true,
        self::HOUR              => true,
        self::MINUTE            => true,
        self::SECOND            => true,
        self::KILOBYTE          => true,
        self::MEGABYTE          => true,
        self::GIGABYTE          => true,
        self::TERABYTE          => true,
        self::ANOTHER           => true,
    );
}
