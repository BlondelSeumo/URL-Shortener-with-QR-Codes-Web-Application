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

namespace YooKassa\Request\Payouts;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payout\IncomeReceipt;

/**
 * Класс, представляющий модель IncomeReceiptData.
 *
 * Данные для формирования чека в сервисе Мой налог. Необходимо передавать, если вы делаете выплату [самозанятому](/developers/payouts/scenario-extensions/self-employed). Только для обычных выплат.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 *
 * @property string $serviceName Описание услуги, оказанной получателем выплаты. Не более 50 символов
 * @property string $service_name Описание услуги, оказанной получателем выплаты. Не более 50 символов
 * @property AmountInterface $amount Сумма для печати в чеке
 */
class IncomeReceiptData extends AbstractObject
{
    /**
     * Описание услуги, оказанной получателем выплаты. Не более 50 символов.
     *
     * @var string
     */
    private $_service_name;

    /**
     * Сумма для печати в чеке.
     *
     * Используется, если сумма в чеке отличается от суммы выплаты. Сумма чека должна быть больше суммы выплаты или равна ей.
     *
     * @var AmountInterface|null
     */
    private $_amount;

    /**
     * Возвращает описание услуги, оказанной получателем выплаты.
     *
     * @return string Описание услуги, оказанной получателем выплаты
     */
    public function getServiceName()
    {
        return $this->_service_name;
    }

    /**
     * Устанавливает описание услуги, оказанной получателем выплаты.
     *
     * @param string $value Описание услуги, оказанной получателем выплаты
     *
     * @return $this
     */
    public function setServiceName($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty service_name value', 0, 'IncomeReceiptData.service_name');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid IncomeReceiptData service_name value type', 0, 'IncomeReceiptData.id', $value);
        }
        if (mb_strlen($value, 'utf-8') > IncomeReceipt::MAX_LENGTH_SERVICE_NAME) {
            throw new InvalidPropertyValueException('Invalid IncomeReceiptData service_name value', 0, 'IncomeReceiptData.service_name', $value);
        }
        $this->_service_name = (string)$value;

        return $this;
    }

    /**
     * Возвращает сумму для печати в чеке.
     *
     * @return AmountInterface|null Сумма для печати в чеке
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Устанавливает сумму для печати в чеке.
     *
     * @param AmountInterface|array|null $value Сумма для печати в чеке
     *
     * @return $this
     */
    public function setAmount($value = null)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty amount value', 0, 'IncomeReceiptData.amount');
        }
        if ($value instanceof AmountInterface) {
            $this->_amount = $value;
        } elseif (is_array($value)) {
            $this->_amount = new MonetaryAmount($value);
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid IncomeReceiptData.amount value type',
                0,
                'IncomeReceiptData.amount',
                $value
            );
        }

        return $this;
    }
}
