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

use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\PaymentMethodType;

/**
 * Класс, описывающий метод оплаты, при оплате через ЮMoney
 *
 * @property string $type Тип объекта
 * @property string $phone Телефон, к которому привязан счет получателя выплаты в системе участника СБП
 * @property string $bank_id Идентификатор выбранного участника СБП — банка или платежного сервиса, подключенного к сервису
 * @property string $bankId Идентификатор выбранного участника СБП — банка или платежного сервиса, подключенного к сервису
 * @property string $recipient_checked Проверка получателя выплаты
 * @property string $recipientChecked Проверка получателя выплаты
 */
class PayoutDestinationSbp extends AbstractPayoutDestination
{
    /**
     * Телефон, к которому привязан счет получателя выплаты в системе участника СБП.
     *
     * Указывается в формате ITU-T E.164, например 79000000000.
     *
     * @var string
     */
    private $_phone;

    /**
     * Идентификатор выбранного участника СБП — банка или платежного сервиса, подключенного к сервису.
     *
     * Максимум 12 символов. [Как получить идентификатор участника СБП](/developers/payouts/making-payouts/sbp)
     *
     * @var string
     */
    private $_bank_id;

    /**
     * Проверка получателя выплаты
     * * true — выплата проходила с проверкой получателя,
     * * false — выплата проходила без проверки получателя.
     *
     * @var bool
     */
    private $_recipient_checked;

    public function __construct()
    {
        $this->setType(PaymentMethodType::SBP);
    }

    /**
     * Возвращает телефон, к которому привязан счет получателя выплаты в системе участника СБП.
     *
     * @return string Телефон, к которому привязан счет получателя выплаты в системе участника СБП
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * Устанавливает телефон, к которому привязан счет получателя выплаты в системе участника СБП.
     *
     * @param string $phone Телефон, к которому привязан счет получателя выплаты в системе участника СБП
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        if ($phone === null || $phone === '') {
            throw new EmptyPropertyValueException('Empty phone value', 0, 'PayoutDestinationSbp.phone');
        }
        if (!TypeCast::canCastToString($phone)) {
            throw new InvalidPropertyValueTypeException('Invalid phone value type', 0, 'PayoutDestinationSbp.phone');
        }
        $this->_phone = (string)preg_replace('/\D/', '', $phone);

        return $this;
    }

    /**
     * Возвращает идентификатор выбранного участника СБП.
     *
     * @return string Идентификатор выбранного участника СБП
     */
    public function getBankId()
    {
        return $this->_bank_id;
    }

    /**
     * Устанавливает идентификатор выбранного участника СБП.
     *
     * @param string $bankId Идентификатор выбранного участника СБП
     *
     * @return $this
     */
    public function setBankId($bankId)
    {
        if ($bankId === null || $bankId === '') {
            throw new EmptyPropertyValueException('Empty bank_id value', 0, 'PayoutDestinationSbp.bank_id');
        }
        if (!TypeCast::canCastToString($bankId)) {
            throw new InvalidPropertyValueTypeException('Invalid bank_id value type', 0, 'PayoutDestinationSbp.bank_id', $bankId);
        }
        if (mb_strlen($bankId) > 12) {
            throw new InvalidPropertyValueException('Invalid bank_id value', 0, 'PayoutDestinationSbp.bank_id', $bankId);
        }
        $this->_bank_id = (string)$bankId;

        return $this;
    }

    /**
     * Возвращает признак тестовой операции
     * @return bool Признак тестовой операции
     */
    public function getRecipientChecked()
    {
        return $this->_recipient_checked;
    }

    /**
     * Устанавливает признак тестовой операции
     * @param bool $value Признак тестовой операции
     */
    public function setRecipientChecked($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty PayoutDestinationSbp recipient_checked flag value',
                0,
                'PayoutDestinationSbp.recipient_checked'
            );
        }
        if (!TypeCast::canCastToBoolean($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid PayoutDestinationSbp recipient_checked flag value type',
                0,
                'PayoutDestinationSbp.recipient_checked',
                $value
            );
        }

        $this->_recipient_checked = (bool)$value;

        return $this;
    }
}
