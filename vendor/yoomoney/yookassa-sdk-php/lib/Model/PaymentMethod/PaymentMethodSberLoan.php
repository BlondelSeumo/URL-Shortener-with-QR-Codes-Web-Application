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

use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\PaymentMethodType;

/**
 * Класс, описывающий метод оплаты, при оплате через Tinkoff
 *
 * @package YooKassa
 *
 * @property string $loan_option Тариф кредита
 * @property string $loanOption Тариф кредита
 * @property AmountInterface $discount_amount Сумма скидки для рассрочки
 * @property AmountInterface $discountAmount Сумма скидки для рассрочки
 */
class PaymentMethodSberLoan extends AbstractPaymentMethod
{
    /**
     * Тариф кредита, который пользователь выбрал при оплате.
     *
     * Возможные значения:
     * * loan — кредит;
     * * installments_XX — рассрочка, где XX — количество месяцев для выплаты рассрочки. Например, installments_3 — рассрочка на 3 месяца.
     *
     * Присутствует для платежей в статусе `waiting_for_capture` и `succeeded`.
     *
     * @var string|null
     */
    private $_loan_option;

    /**
     * Сумма скидки для рассрочки.
     *
     * Присутствует для платежей в статусе `waiting_for_capture` и `succeeded`, если пользователь выбрал рассрочку.
     *
     * @var AmountInterface|null
     */
    private $_discount_amount;

    public function __construct()
    {
        $this->setType(PaymentMethodType::SBER_LOAN);
    }


    /**
     * Возвращает тариф кредита.
     * @return string Тариф кредита
     */
    public function getLoanOption()
    {
        return $this->_loan_option;
    }

    /**
     * Устанавливает тариф кредита.
     * @param string|null $value Тариф кредита
     * @return PaymentMethodSberLoan
     */
    public function setLoanOption($value)
    {
        if ($value === null || $value === '') {
            $this->_loan_option = null;
            return $this;
        }

        if (TypeCast::canCastToString($value)) {
            if (preg_match('/^loan|installments_([0-9]{1,})$/', $value)) {
                $this->_loan_option = (string)$value;
            } else {
                throw new InvalidPropertyValueException(
                    'Invalid loan_option value',
                    0,
                    'PaymentMethodSberLoan.loan_option',
                    $value
                );
            }
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid loan_option value type',
                0,
                'PaymentMethodSberLoan.loan_option',
                $value
            );
        }

        return $this;
    }


    /**
     * Возвращает сумму
     * @return AmountInterface|null Сумма платежа
     */
    public function getDiscountAmount()
    {
        return $this->_discount_amount;
    }

    /**
     * Устанавливает сумму платежа
     * @param AmountInterface|array|null $value Сумма платежа
     * @return PaymentMethodSberLoan
     */
    public function setDiscountAmount($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->_discount_amount = null;
        } elseif ($value instanceof AmountInterface) {
            $this->_discount_amount = $value;
        } elseif (is_array($value)) {
            $this->_discount_amount = new MonetaryAmount($value);
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid PaymentMethodSberLoan.discount_amount value type',
                0,
                'PaymentMethodSberLoan.discount_amount',
                $value
            );
        }

        return $this;
    }
}
