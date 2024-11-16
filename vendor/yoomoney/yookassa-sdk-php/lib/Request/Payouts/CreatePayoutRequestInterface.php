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

use YooKassa\Model\AmountInterface;
use YooKassa\Model\Metadata;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Deal\PayoutDealInfo;
use YooKassa\Model\Payout\AbstractPayoutDestination;

/**
 * Interface CreatePayoutRequestInterface
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
interface CreatePayoutRequestInterface
{
    /**
     * Возвращает сумму выплаты
     * @return AmountInterface|MonetaryAmount Сумма выплаты
     */
    public function getAmount();

    /**
     * Проверяет наличие суммы в создаваемой выплате
     * @return bool True если сумма установлена, false если нет
     */
    public function hasAmount();

    /**
     * Устанавливает сумму выплаты
     * @param AmountInterface|array $value Сумма выплаты
     */
    public function setAmount($value);

    /**
     * Возвращает данные платежного средства, на которое нужно сделать выплату
     * @return AbstractPayoutDestination|null Данные платежного средства, на которое нужно сделать выплату
     */
    public function getPayoutDestinationData();

    /**
     * Проверяет наличие данных платежного средства, на которое нужно сделать выплату
     * @return bool True если данные есть, false если нет
     */
    public function hasPayoutDestinationData();

    /**
     * Устанавливает данные платежного средства, на которое нужно сделать выплату
     * @param AbstractPayoutDestination|array|null $value Данные платежного средства, на которое нужно сделать выплату
     */
    public function setPayoutDestinationData($value);

    /**
     * Возвращает токенизированные данные для выплаты
     * @return string Токенизированные данные для выплаты
     */
    public function getPayoutToken();

    /**
     * Проверяет наличие токенизированных данных для выплаты
     * @return bool True если токен установлен, false если нет
     */
    public function hasPayoutToken();

    /**
     * Устанавливает токенизированные данные для выплаты
     * @param string $value Токенизированные данные для выплаты
     */
    public function setPayoutToken($value);

    /**
     * Возвращает идентификатор сохраненного способа оплаты.
     *
     * @return string|null Идентификатор сохраненного способа оплаты
     */
    public function getPaymentMethodId();

    /**
     * Проверяет наличие идентификатора сохраненного способа оплаты
     * @return bool True если идентификатора установлен, false если нет
     */
    public function hasPaymentMethodId();

    /**
     * Устанавливает идентификатор сохраненного способа оплаты.
     *
     * @param string|null $value Идентификатор сохраненного способа оплаты
     *
     * @return $this
     */
    public function setPaymentMethodId($value = null);

    /**
     * Возвращает описание транзакции
     * @return string Описание транзакции
     */
    public function getDescription();

    /**
     * Проверяет наличие описания транзакции в создаваемой выплате
     * @return bool True если описание транзакции установлено, false если нет
     */
    public function hasDescription();

    /**
     * Устанавливает описание транзакции
     * @param string $value Описание транзакции
     */
    public function setDescription($value);

    /**
     * Возвращает сделку, в рамках которой нужно провести выплату
     * @return PayoutDealInfo|null Сделка, в рамках которой нужно провести выплату
     */
    public function getDeal();

    /**
     * Проверяет установлена ли сделка, в рамках которой нужно провести выплату
     * @return bool True если сделка установлена, false если нет
     */
    public function hasDeal();

    /**
     * Устанавливает сделку, в рамках которой нужно провести выплату
     * @param PayoutDealInfo|array|null $value Сделка, в рамках которой нужно провести выплату
     */
    public function setDeal($value);

    /**
     * Возвращает данные самозанятого, который получит выплату.
     *
     * @return PayoutSelfEmployedInfo|null Данные самозанятого, который получит выплату
     */
    public function getSelfEmployed();

    /**
     * Проверяет наличие данных самозанятого в создаваемой выплате
     * @return bool True если данные самозанятого есть, false если нет
     */
    public function hasSelfEmployed();

    /**
     * Устанавливает данные самозанятого, который получит выплату.
     *
     * @param PayoutSelfEmployedInfo|array|null $value Данные самозанятого, который получит выплату
     *
     * @return $this
     */
    public function setSelfEmployed($value = null);

    /**
     * Возвращает данные для формирования чека в сервисе Мой налог.
     *
     * @return IncomeReceiptData|null Данные для формирования чека в сервисе Мой налог
     */
    public function getReceiptData();

    /**
     * Проверяет наличие данных для формирования чека в сервисе Мой налог.
     * @return bool True если данные для формирования чека есть, false если нет
     */
    public function hasReceiptData();

    /**
     * Устанавливает данные для формирования чека в сервисе Мой налог..
     *
     * @param IncomeReceiptData|array|null $value Данные для формирования чека в сервисе Мой налог.
     *
     * @return $this
     */
    public function setReceiptData($value = null);

    /**
     * Возвращает персональные данные получателя выплаты.
     *
     * @return PayoutPersonalData[]|null Персональные данные получателя выплаты
     */
    public function getPersonalData();

    /**
     * Проверяет наличие персональных данных в создаваемой выплате
     * @return bool True если персональные данные есть, false если нет
     */
    public function hasPersonalData();

    /**
     * Устанавливает персональные данные получателя выплаты.
     *
     * @param PayoutPersonalData[]|array|null $value Персональные данные получателя выплаты
     *
     * @return $this
     */
    public function setPersonalData($value = null);

    /**
     * Возвращает данные оплаты установленные мерчантом
     * @return Metadata Метаданные привязанные к выплате
     */
    public function getMetadata();

    /**
     * Проверяет, были ли установлены метаданные заказа
     * @return bool True если метаданные были установлены, false если нет
     */
    public function hasMetadata();

    /**
     * Устанавливает метаданные, привязанные к выплате
     * @param Metadata|array|null $value Метаданные платежа, устанавливаемые мерчантом
     */
    public function setMetadata($value);
}
