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

namespace YooKassa\Model\Payout;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\MonetaryAmount;

/**
 * Класс, представляющий модель IncomeReceipt.
 *
 * Данные чека, зарегистрированного в ФНС. Присутствует, если вы делаете выплату [самозанятому](/developers/payouts/scenario-extensions/self-employed).
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 *
 * @property string $service_name Описание услуги, оказанной получателем выплаты. Не более 50 символов.
 * @property string $serviceName Описание услуги, оказанной получателем выплаты. Не более 50 символов.
 * @property string|null $npd_receipt_id Идентификатор чека в сервисе.
 * @property string|null $npdReceiptId Идентификатор чека в сервисе.
 * @property string|null $url Ссылка на зарегистрированный чек.
 * @property AmountInterface|null $amount Сумма, указанная в чеке. Присутствует, если в запросе передавалась сумма для печати в чеке.
 */
class IncomeReceipt extends AbstractObject
{
    /** @var int Максимальная длина описание услуги */
    const MAX_LENGTH_SERVICE_NAME = 50;

    /**
     * Описание услуги, оказанной получателем выплаты. Не более 50 символов.
     *
     * @var string
     */
    private $_service_name;

    /**
     * Идентификатор чека в сервисе.
     *
     * Пример: ~`208jd98zqe`
     *
     * @var string|null
     */
    private $_npd_receipt_id;

    /**
     * Ссылка на зарегистрированный чек.
     *
     * Пример: ~`https://www.nalog.gov.ru/api/v1/receipt/<Идентификатор чека>/print`
     *
     * @var string|null
     */
    private $_url;

    /**
     * Сумма, указанная в чеке. Присутствует, если в запросе передавалась сумма для печати в чеке.
     *
     * @var AmountInterface|null
     */
    private $_amount;


    /**
     * Возвращает service_name.
     *
     * @return string
     */
    public function getServiceName()
    {
        return $this->_service_name;
    }

    /**
     * Устанавливает service_name.
     *
     * @param string $value Описание услуги, оказанной получателем выплаты. Не более 50 символов.
     *
     * @return $this
     */
    public function setServiceName($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty service_name value', 0, 'IncomeReceipt.service_name');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid service_name value type',
                0,
                'IncomeReceipt.service_name',
                $value
            );
        }
        if (mb_strlen($value, 'utf-8') > self::MAX_LENGTH_SERVICE_NAME) {
            throw new InvalidPropertyValueException('Invalid IncomeReceipt service_name value', 0, 'IncomeReceipt.service_name', $value);
        }
        $this->_service_name = (string)$value;

        return $this;
    }

    /**
     * Возвращает npd_receipt_id.
     *
     * @return string|null
     */
    public function getNpdReceiptId()
    {
        return $this->_npd_receipt_id;
    }

    /**
     * Устанавливает npd_receipt_id.
     *
     * @param string|null $value Идентификатор чека в сервисе.
     *
     * Пример: ~`208jd98zqe`
     *
     * @return $this
     */
    public function setNpdReceiptId($value = null)
    {
        if ($value === null || $value === '') {
            $this->_npd_receipt_id = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_npd_receipt_id = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid npd_receipt_id value type',
                0,
                'IncomeReceipt.npd_receipt_id',
                $value
            );
        }

        return $this;
    }

    /**
     * Возвращает Ссылка на зарегистрированный чек.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * Устанавливает ссылка на зарегистрированный чек.
     *
     * @param string|null $value Ссылка на зарегистрированный чек
     *
     * Пример: ~`https://www.nalog.gov.ru/api/v1/receipt/<Идентификатор чека>/print`
     *
     * @return $this
     */
    public function setUrl($value = null)
    {
        if ($value === null || $value === '') {
            $this->_url = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_url = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid url value type',
                0,
                'IncomeReceipt.url',
                $value
            );
        }

        return $this;
    }

    /**
     * Возвращает amount.
     *
     * @return AmountInterface|null
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Устанавливает amount.
     *
     * @param AmountInterface|array|null $value
     *
     * @return $this
     */
    public function setAmount($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->_amount = null;
        } elseif ($value instanceof AmountInterface) {
            $this->_amount = $value;
        } elseif (is_array($value)) {
            $this->_amount = new MonetaryAmount($value);
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid IncomeReceipt.amount value type',
                0,
                'IncomeReceipt.amount',
                $value
            );
        }

        return $this;
    }
}
