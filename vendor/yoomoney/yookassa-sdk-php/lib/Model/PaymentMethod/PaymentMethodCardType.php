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

namespace YooKassa\Model\PaymentMethod;

use YooKassa\Common\AbstractEnum;

/**
 * PaymentMethodCardType - Тип банковской карты.
 *
 * Возможные значения:
 * - `MasterCard` (для карт Mastercard и Maestro),
 * - `Visa` (для карт Visa и Visa Electron),
 * - `Mir`,
 * - `UnionPay`,
 * - `JCB`,
 * - `AmericanExpress`,
 * - `DinersClub`,
 * - `DiscoverCard`,
 * - `InstaPayment`,
 * - `InstaPaymentTM`,
 * - `Laser`,
 * - `Dankort`,
 * - `Solo`,
 * - `Switch`,
 * - `Unknown`.
 */
class PaymentMethodCardType extends AbstractEnum
{
    const MASTER_CARD = 'MasterCard';
    const VISA = 'Visa';
    const MIR = 'Mir';
    const UNION_PAY = 'UnionPay';
    const JCB = 'JCB';
    const AMERICAN_EXPRESS = 'AmericanExpress';
    const DINERS_CLUB = 'DinersClub';
    const DISCOVER_CARD_CLUB = 'DiscoverCard';
    const INSTA_PAYMENT_CLUB = 'InstaPayment';
    const INSTA_PAYMENT_TM_CLUB = 'InstaPaymentTM';
    const LASER_CLUB = 'Laser';
    const DANKORT_CLUB = 'Dankort';
    const SOLO_CLUB = 'Solo';
    const SWITCH_CLUB = 'Switch';
    const UNKNOWN = 'Unknown';

    protected static $validValues = array(
        self::MASTER_CARD           => true,
        self::VISA                  => true,
        self::MIR                   => true,
        self::UNION_PAY             => true,
        self::JCB                   => true,
        self::AMERICAN_EXPRESS      => true,
        self::DINERS_CLUB           => true,
        self::DISCOVER_CARD_CLUB    => true,
        self::INSTA_PAYMENT_CLUB    => true,
        self::INSTA_PAYMENT_TM_CLUB => true,
        self::LASER_CLUB            => true,
        self::DANKORT_CLUB          => true,
        self::SOLO_CLUB             => true,
        self::SWITCH_CLUB           => true,
        self::UNKNOWN               => true,
    );
}
