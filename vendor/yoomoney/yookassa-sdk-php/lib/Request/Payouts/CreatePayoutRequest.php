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

use YooKassa\Common\AbstractRequest;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\Deal\PayoutDealInfo;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payout;
use YooKassa\Model\Metadata;
use YooKassa\Model\Payout\AbstractPayoutDestination;
use YooKassa\Request\Payouts\PayoutDestinationData\AbstractPayoutDestinationData;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataFactory;

/**
 * Класс объекта запроса к API на проведение новой выплаты
 *
 * @todo: @example 02-builder.php 11 78 Пример использования билдера
 *
 * @package YooKassa
 *
 * @property AmountInterface $amount Сумма создаваемой выплаты
 * @property AbstractPayoutDestination $payoutDestinationData Данные платежного средства, на которое нужно сделать выплату. Обязательный параметр, если не передан payout_token.
 * @property AbstractPayoutDestination $payout_destination_data Данные платежного средства, на которое нужно сделать выплату. Обязательный параметр, если не передан payout_token.
 * @property string $payoutToken Токенизированные данные для выплаты. Например, синоним банковской карты. Обязательный параметр, если не передан payout_destination_data
 * @property string $payout_token Токенизированные данные для выплаты. Например, синоним банковской карты. Обязательный параметр, если не передан payout_destination_data
 * @property string $payment_method_id Идентификатор сохраненного способа оплаты, данные которого нужно использовать для проведения выплаты
 * @property string $paymentMethodId Идентификатор сохраненного способа оплаты, данные которого нужно использовать для проведения выплаты
 * @property PayoutDealInfo $deal Сделка, в рамках которой нужно провести выплату. Необходимо передавать, если вы проводите Безопасную сделку
 * @property string $description Описание транзакции (не более 128 символов). Например: «Выплата по договору N»
 * @property PayoutSelfEmployedInfo $self_employed Данные самозанятого, который получит выплату. Необходимо передавать, если вы делаете выплату [самозанятому](https://yookassa.ru/developers/payouts/scenario-extensions/self-employed). Только для обычных выплат.
 * @property PayoutSelfEmployedInfo $selfEmployed Данные самозанятого, который получит выплату. Необходимо передавать, если вы делаете выплату [самозанятому](https://yookassa.ru/developers/payouts/scenario-extensions/self-employed). Только для обычных выплат.
 * @property IncomeReceiptData $receipt_data Данные для формирования чека в сервисе Мой налог. Необходимо передавать, если вы делаете выплату [самозанятому](https://yookassa.ru/developers/payouts/scenario-extensions/self-employed). Только для обычных выплат.
 * @property IncomeReceiptData $receiptData Данные для формирования чека в сервисе Мой налог. Необходимо передавать, если вы делаете выплату [самозанятому](https://yookassa.ru/developers/payouts/scenario-extensions/self-employed). Только для обычных выплат.
 * @property PayoutPersonalData $personal_data Персональные данные получателя выплаты. Необходимо передавать, если вы делаете выплаты с [проверкой получателя](/developers/payouts/scenario-extensions/recipient-check) (только для выплат через СБП).
 * @property PayoutPersonalData $personalData Персональные данные получателя выплаты. Необходимо передавать, если вы делаете выплаты с [проверкой получателя](/developers/payouts/scenario-extensions/recipient-check) (только для выплат через СБП).
 * @property Metadata $metadata Метаданные привязанные к выплате
 */
class CreatePayoutRequest extends AbstractRequest implements CreatePayoutRequestInterface
{
    /**
     * @var AmountInterface Сумма создаваемой выплаты
     */
    private $_amount;

    /**
     * @var AbstractPayoutDestination Данные платежного средства, на которое нужно сделать выплату
     */
    private $_payout_destination_data;

    /**
     * @var string Токенизированные данные для выплаты
     */
    private $_payoutToken;

    /**
     * Идентификатор сохраненного способа оплаты, данные которого нужно использовать для проведения выплаты.
     *
     * [Подробнее о выплатах с использованием идентификатора сохраненного способа оплаты](https://yookassa.ru/developers/payouts/scenario-extensions/multipurpose-token)
     *
     * Обязательный параметр, если не передан payout_destination_data или payout_token.
     *
     * @var string|null
     */
    private $_payment_method_id;

    /**
     * @var PayoutDealInfo Сделка, в рамках которой нужно провести выплату
     */
    private $_deal;

    /**
     * @var string Описание транзакции
     */
    private $_description;

    /**
     * Данные самозанятого, который получит выплату. Необходимо передавать, если вы делаете выплату [самозанятому](https://yookassa.ru/developers/payouts/scenario-extensions/self-employed). Только для обычных выплат.
     *
     * @var PayoutSelfEmployedInfo|null
     */
    private $_self_employed;

    /**
     * Данные для формирования чека в сервисе Мой налог. Необходимо передавать, если вы делаете выплату [самозанятому](https://yookassa.ru/developers/payouts/scenario-extensions/self-employed). Только для обычных выплат.
     *
     * @var IncomeReceiptData|null
     */
    private $_receipt_data;

    /**
     * Персональные данные получателя выплаты. Необходимо передавать, если вы делаете выплаты с [проверкой получателя](/developers/payouts/scenario-extensions/recipient-check) (только для выплат через СБП).
     *
     * @var PayoutPersonalData[]|null
     */
    private $_personal_data;

    /**
     * @var Metadata Метаданные привязанные к выплате
     */
    private $_metadata;

    /**
     * Возвращает сумму выплаты
     * @return AmountInterface Сумма выплаты
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Проверяет, была ли установлена сумма выплаты
     * @return bool True если сумма выплаты была установлена, false если нет
     */
    public function hasAmount()
    {
        return !empty($this->_amount);
    }

    /**
     * Устанавливает сумму выплаты
     * @param AmountInterface|array|string|numeric|null $value Сумма выплаты
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если был передан объект невалидного типа
     */
    public function setAmount($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty amount value', 0, 'CreatePayoutRequest.amount');
        }
        if ($value instanceof AmountInterface) {
            $this->_amount = $value;
        } elseif (is_numeric($value) || is_array($value)) {
            $this->_amount = new MonetaryAmount($value);
        } else {
            throw new InvalidPropertyValueTypeException('Invalid amount value type in CreatePayoutRequest', 0, 'CreatePayoutRequest.amount', $value);
        }
        $value = $this->_amount->getValue();
        if (empty($value) || $value <= 0.0) {
            throw new InvalidPropertyValueException('Invalid amount value in CreatePayoutRequest', 0, 'CreatePayoutRequest.amount', $value);
        }
    }

    /**
     * Возвращает данные для создания метода оплаты
     * @return AbstractPayoutDestination Данные используемые для создания метода оплаты
     */
    public function getPayoutDestinationData()
    {
        return $this->_payout_destination_data;
    }

    /**
     * Проверяет установлен ли объект с методом оплаты
     * @return bool True если объект метода оплаты установлен, false если нет
     */
    public function hasPayoutDestinationData()
    {
        return !empty($this->_payout_destination_data);
    }

    /**
     * Устанавливает объект с информацией для создания метода оплаты
     * @param AbstractPayoutDestinationData|array|null $value Объект создания метода оплаты или null
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если был передан объект невалидного типа
     */
    public function setPayoutDestinationData($value)
    {
        if ($value === null || $value === '') {
            $this->_payout_destination_data = null;
        } elseif ($value instanceof AbstractPayoutDestinationData) {
            $this->_payout_destination_data = $value;
        } elseif (is_array($value)) {
            $factory = new PayoutDestinationDataFactory();
            $this->_payout_destination_data = $factory->factoryFromArray($value);
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid payoutDestinationData value type in CreatePayoutRequest',
                0,
                'CreatePayoutRequest.payoutDestinationData',
                $value
            );
        }
    }

    /**
     * Возвращает токенизированные данные для выплаты
     * @return string Токенизированные данные для выплаты
     */
    public function getPayoutToken()
    {
        return $this->_payoutToken;
    }

    /**
     * Проверяет наличие токенизированных данных для выплаты
     * @return bool True если токен установлен, false если нет
     */
    public function hasPayoutToken()
    {
        return !empty($this->_payoutToken);
    }

    /**
     * Устанавливает токенизированные данные для выплаты
     * @param string $value Токенизированные данные для выплаты
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если переданное значение не является строкой
     */
    public function setPayoutToken($value)
    {
        if ($value === null || $value === '') {
            $this->_payoutToken = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_payoutToken = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid payoutToken value type',
                0,
                'CreatePayoutRequest.payoutToken',
                $value
            );
        }
    }

    /**
     * Возвращает идентификатор сохраненного способа оплаты.
     *
     * @return string|null Идентификатор сохраненного способа оплаты
     */
    public function getPaymentMethodId()
    {
        return $this->_payment_method_id;
    }

    /**
     * Проверяет наличие идентификатора сохраненного способа оплаты
     * @return bool True если идентификатора установлен, false если нет
     */
    public function hasPaymentMethodId()
    {
        return !empty($this->_payment_method_id);
    }

    /**
     * Устанавливает идентификатор сохраненного способа оплаты.
     *
     * @param string|null $value Идентификатор сохраненного способа оплаты
     *
     * @return $this
     */
    public function setPaymentMethodId($value = null)
    {
        if ($value === null || $value === '') {
            $this->_payment_method_id = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_payment_method_id = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid payment_method_id value type',
                0,
                'CreatePayoutRequest.payment_method_id',
                $value
            );
        }

        return $this;
    }

    /**
     * Возвращает сделку, в рамках которой нужно провести выплату
     * @return PayoutDealInfo Сделка, в рамках которой нужно провести выплату
     */
    public function getDeal()
    {
        return $this->_deal;
    }

    /**
     * Проверяет наличие сделки в создаваемой выплате
     * @return bool True если сделка есть, false если нет
     */
    public function hasDeal()
    {
        return !empty($this->_deal);
    }

    /**
     * Устанавливает сделку, в рамках которой нужно провести выплату
     * @param PayoutDealInfo|array $value Сделка, в рамках которой нужно провести выплату
     */
    public function setDeal($value)
    {
        if ($value === null || $value === '') {
            $this->_deal = null;
        } elseif (is_array($value)) {
            $this->_deal = new PayoutDealInfo($value);
        } elseif ($value instanceof PayoutDealInfo) {
            $this->_deal = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "deal" parameter in CreatePayoutRequest',
                0,
                'CreatePayoutRequest.deal',
                $value
            );
        }
        return $this;
    }
    /**
     * Возвращает данные самозанятого, который получит выплату.
     *
     * @return PayoutSelfEmployedInfo|null Данные самозанятого, который получит выплату
     */
    public function getSelfEmployed()
    {
        return $this->_self_employed;
    }

    /**
     * Проверяет наличие данных самозанятого в создаваемой выплате
     * @return bool True если данные самозанятого есть, false если нет
     */
    public function hasSelfEmployed()
    {
        return !empty($this->_self_employed);
    }

    /**
     * Устанавливает данные самозанятого, который получит выплату.
     *
     * @param PayoutSelfEmployedInfo|array|null $value Данные самозанятого, который получит выплату
     *
     * @return $this
     */
    public function setSelfEmployed($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->_self_employed = null;
        } elseif (is_array($value)) {
            $this->_self_employed = new PayoutSelfEmployedInfo($value);
        } elseif ($value instanceof PayoutSelfEmployedInfo) {
            $this->_self_employed = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "self_employed" parameter in CreatePayoutRequest',
                0,
                'CreatePayoutRequest.self_employed',
                $value
            );
        }

        return $this;
    }

    /**
     * Возвращает данные для формирования чека в сервисе Мой налог.
     *
     * @return IncomeReceiptData|null Данные для формирования чека в сервисе Мой налог
     */
    public function getReceiptData()
    {
        return $this->_receipt_data;
    }

    /**
     * Проверяет наличие данных для формирования чека в сервисе Мой налог.
     * @return bool True если данные для формирования чека есть, false если нет
     */
    public function hasReceiptData()
    {
        return !empty($this->_receipt_data);
    }

    /**
     * Устанавливает данные для формирования чека в сервисе Мой налог.
     *
     * @param IncomeReceiptData|array|null $value Данные для формирования чека в сервисе Мой налог
     *
     * @return $this
     */
    public function setReceiptData($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->_receipt_data = null;
        } elseif (is_array($value)) {
            $this->_receipt_data = new IncomeReceiptData($value);
        } elseif ($value instanceof IncomeReceiptData) {
            $this->_receipt_data = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "receipt_data" parameter in CreatePayoutRequest',
                0,
                'CreatePayoutRequest.receipt_data',
                $value
            );
        }

        return $this;
    }

    /**
     * Возвращает персональные данные получателя выплаты.
     *
     * @return PayoutPersonalData[]|null Персональные данные получателя выплаты
     */
    public function getPersonalData()
    {
        return $this->_personal_data;
    }

    /**
     * Проверяет наличие персональных данных в создаваемой выплате
     * @return bool True если персональные данные есть, false если нет
     */
    public function hasPersonalData()
    {
        return !empty($this->_personal_data);
    }

    /**
     * Устанавливает персональные данные получателя выплаты.
     *
     * @param PayoutPersonalData[]|array|null $value Персональные данные получателя выплаты
     *
     * @return $this
     */
    public function setPersonalData($value = null)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->_personal_data = null;
            return $this;
        }
        if (!is_array($value) && !($value instanceof \Traversable)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid personal_data value type in CreatePayoutRequest',
                0,
                'CreatePayoutRequest.personal_data',
                $value
            );
        }
        $this->_personal_data = array();
        foreach ($value as $key => $val) {
            if ($val instanceof PayoutPersonalData) {
                $this->_personal_data[] = $val;
            } elseif (is_array($val)) {
                $this->_personal_data[] = new PayoutPersonalData($val);
            } else {
                throw new InvalidPropertyValueTypeException(
                    'Invalid personal_data value type in CreatePayoutRequest',
                    0,
                    'CreatePayoutRequest.personal_data[' . $key . ']',
                    $val
                );
            }
        }

        return $this;
    }

    /**
     * Возвращает описание транзакции
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Проверяет наличие описания транзакции в создаваемом платеже
     * @return bool True если описание транзакции есть, false если нет
     */
    public function hasDescription()
    {
        return $this->_description !== null;
    }

    /**
     * Устанавливает описание транзакции
     * @param string $value
     *
     * @throws InvalidPropertyValueException Выбрасывается если переданное значение превышает допустимую длину
     * @throws InvalidPropertyValueTypeException Выбрасывается если переданное значение не является строкой
     */
    public function setDescription($value)
    {
        if ($value === null || $value === '') {
            $this->_description = null;
        } elseif (TypeCast::canCastToString($value)) {
            $length = mb_strlen((string)$value, 'utf-8');
            if ($length > Payout::MAX_LENGTH_DESCRIPTION) {
                throw new InvalidPropertyValueException(
                    'The value of the description parameter is too long. Max length is ' . Payout::MAX_LENGTH_DESCRIPTION,
                    0,
                    'CreatePayoutRequest.description',
                    $value
                );
            }
            $this->_description = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid description value type',
                0,
                'CreatePayoutRequest.description',
                $value
            );
        }
    }

    /**
     * Возвращает данные оплаты установленные мерчантом
     * @return Metadata Метаданные, привязанные к выплате
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }

    /**
     * Проверяет, были ли установлены метаданные выплаты
     * @return bool True если метаданные были установлены, false если нет
     */
    public function hasMetadata()
    {
        return !empty($this->_metadata) && $this->_metadata->count() > 0;
    }

    /**
     * Устанавливает метаданные, привязанные к выплате
     * @param Metadata|array|null $value Метаданные выплаты, устанавливаемые мерчантом
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если переданные данные не удалось интерпретировать как
     * метаданные выплаты
     */
    public function setMetadata($value)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->_metadata = null;
        } elseif ($value instanceof Metadata) {
            $this->_metadata = $value;
        } elseif (is_array($value)) {
            $this->_metadata = new Metadata($value);
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid metadata value type in CreatePayoutRequest',
                0,
                'CreatePayoutRequest.metadata',
                $value
            );
        }
    }

    /**
     * Проверяет на валидность текущий объект
     * @return bool True если объект запроса валиден, false если нет
     */
    public function validate()
    {
        if (!$this->hasAmount()) {
            $this->setValidationError('Amount field is required');
            return false;
        }
        if ($this->hasPayoutToken() && $this->hasPayoutDestinationData()) {
            $this->setValidationError('Both payoutToken and payoutDestinationData values are specified');
            return false;
        }

        if (!$this->hasPayoutToken() && !$this->hasPayoutDestinationData()) {
            $this->setValidationError('Both payoutToken and payoutDestinationData values are not specified');
            return false;
        }

        return true;
    }

    /**
     * Возвращает билдер объектов запросов создания выплаты
     * @return CreatePayoutRequestBuilder Инстанс билдера объектов запросов
     */
    public static function builder()
    {
        return new CreatePayoutRequestBuilder();
    }
}
