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

namespace YooKassa\Model;

use YooKassa\Common\AbstractEnum;

/**
 * CurrencyCode - Код валюты в формате [ISO-4217](https://www.iso.org/iso-4217-currency-codes.html).
 * Должен соответствовать валюте субаккаунта (`recipient.gateway_id`), если вы разделяете потоки платежей,
 * и валюте аккаунта (shopId в [личном кабинете](https://yookassa.ru/my)), если не разделяете.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 */
class CurrencyCode extends AbstractEnum
{
    /** Российский рубль */
    const RUB = 'RUB';
    /** Доллар США */
    const USD = 'USD';
    /** Евро */
    const EUR = 'EUR';
    /** Белорусский рубль */
    const BYN = 'BYN';
    /** Китайская йена */
    const CNY = 'CNY';
    /** Казахский тенге */
    const KZT = 'KZT';
    /** Украинская гривна */
    const UAH = 'UAH';
    /** Узбекский сум */
    const UZS = 'UZS';
    /** Турецкая лира */
    const _TRY = 'TRY';
    /** Индийская рупия */
    const INR = 'INR';
    /** Молдавский лей */
    const MDL = 'MDL';
    /** Азербайджанский манат */
    const AZN = 'AZN';

    protected static $validValues = array(
        self::RUB => true,
        self::USD => true,
        self::EUR => true,
        self::BYN => true,
        self::CNY => true,
        self::KZT => true,
        self::UAH => true,
        self::UZS => true,
        self::_TRY => true,
        self::INR => true,
        self::MDL => true,
        self::AZN => true,
    );
}
