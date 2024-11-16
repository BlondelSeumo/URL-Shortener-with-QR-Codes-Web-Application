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

namespace YooKassa\Request\Payments;

use YooKassa\Model\Airline;
use YooKassa\Model\AirlineInterface;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\ConfirmationAttributes\AbstractConfirmationAttributes;
use YooKassa\Model\Deal\PaymentDealInfo;
use YooKassa\Model\FraudData;
use YooKassa\Model\Metadata;
use YooKassa\Model\PaymentData\AbstractPaymentData;
use YooKassa\Model\ReceiptInterface;
use YooKassa\Model\RecipientInterface;
use YooKassa\Model\TransferInterface;

/**
 * Interface CreatePaymentRequestInterface
 *
 * @package YooKassa
 *
 * @property-read RecipientInterface|null $recipient Получатель платежа, если задан
 * @property-read AmountInterface $amount Сумма создаваемого платежа
 * @property-read ReceiptInterface $receipt Данные фискального чека 54-ФЗ
 * @property-read string $paymentToken Одноразовый токен для проведения оплаты, сформированный YooKassa JS widget
 * @property-read string $payment_token Одноразовый токен для проведения оплаты, сформированный YooKassa JS widget
 * @property-read string $paymentMethodId Идентификатор записи о сохраненных платежных данных покупателя
 * @property-read string $payment_method_id Идентификатор записи о сохраненных платежных данных покупателя
 * @property-read AbstractPaymentData $paymentMethodData Данные используемые для создания метода оплаты
 * @property-read AbstractPaymentData $payment_method_data Данные используемые для создания метода оплаты
 * @property-read AbstractConfirmationAttributes $confirmation Способ подтверждения платежа
 * @property-read bool $savePaymentMethod Сохранить платежные данные для последующего использования
 * @property-read bool $save_payment_method Сохранить платежные данные для последующего использования
 * @property-read bool $capture Автоматически принять поступившую оплату
 * @property-read string $clientIp IPv4 или IPv6-адрес покупателя. Если не указан, используется IP-адрес TCP-подключения.
 * @property-read string $client_ip IPv4 или IPv6-адрес покупателя. Если не указан, используется IP-адрес TCP-подключения.
 * @property-read Metadata $metadata Метаданные привязанные к платежу
 * @property-read TransferInterface[] $transfers Метаданные привязанные к платежу
 * @property-read PaymentDealInfo $deal Данные о сделке, в составе которой проходит платеж
 * @property-read FraudData $fraudData Информация для проверки операции на мошенничество
 * @property-read FraudData $fraud_data Информация для проверки операции на мошенничество
 * @property-read string $merchantCustomerId Идентификатор покупателя в вашей системе, например электронная почта или номер телефона
 * @property-read string $merchant_customer_id Идентификатор покупателя в вашей системе, например электронная почта или номер телефона
 */
interface CreatePaymentRequestInterface
{
    /**
     * Возвращает объект получателя платежа
     * @return RecipientInterface|null Объект с информацией о получателе платежа или null, если получатель не задан
     */
    public function getRecipient();

    /**
     * Проверяет наличие получателя платежа в запросе
     * @return bool True если получатель платежа задан, false если нет
     */
    public function hasRecipient();

    /**
     * Устанавливает объект с информацией о получателе платежа
     * @param RecipientInterface|null $value Инстанс объекта информации о получателе платежа или null
     */
    public function setRecipient($value);

    /**
     * Возвращает сумму заказа
     * @return AmountInterface Сумма заказа
     */
    public function getAmount();

    /**
     * Возвращает описание транзакции
     * @return string Описание транзакции
     */
    public function getDescription();

    /**
     * Проверяет наличие описания транзакции в создаваемом платеже
     * @return bool True если описание транзакции установлено, false если нет
     */
    public function hasDescription();

    /**
     * Устанавливает описание транзакции
     * @param string $value Описание транзакции
     */
    public function setDescription($value);

    /**
     * Возвращает чек, если он есть
     * @return ReceiptInterface|null Данные фискального чека 54-ФЗ или null, если чека нет
     */
    public function getReceipt();

    /**
     * Проверяет наличие чека в создаваемом платеже
     * @return bool True если чек есть, false если нет
     */
    public function hasReceipt();

    /**
     * Возвращает одноразовый токен для проведения оплаты
     * @return string Одноразовый токен для проведения оплаты, сформированный YooKassa JS widget
     */
    public function getPaymentToken();

    /**
     * Проверяет наличие одноразового токена для проведения оплаты
     * @return bool True если токен установлен, false если нет
     */
    public function hasPaymentToken();

    /**
     * Устанавливает одноразовый токен для проведения оплаты, сформированный YooKassa JS widget
     * @param string $value Одноразовый токен для проведения оплаты
     */
    public function setPaymentToken($value);

    /**
     * Устанавливает идентификатор записи платёжных данных покупателя
     * @return string Идентификатор записи о сохраненных платежных данных покупателя
     */
    public function getPaymentMethodId();

    /**
     * Проверяет наличие идентификатора записи о платёжных данных покупателя
     * @return bool True если идентификатор задан, false если нет
     */
    public function hasPaymentMethodId();

    /**
     * Устанавливает идентификатор записи о сохранённых данных покупателя
     * @param string $value Идентификатор записи о сохраненных платежных данных покупателя
     */
    public function setPaymentMethodId($value);

    /**
     * Возвращает данные для создания метода оплаты
     * @return AbstractPaymentData Данные используемые для создания метода оплаты
     */
    public function getPaymentMethodData();

    /**
     * Проверяет установлен ли объект с методом оплаты
     * @return bool True если объект метода оплаты установлен, false если нет
     */
    public function hasPaymentMethodData();

    /**
     * Устанавливает объект с информацией для создания метода оплаты
     * @param AbstractPaymentData|null $value Объект создания метода оплаты или null
     */
    public function setPaymentMethodData($value);

    /**
     * Возвращает способ подтверждения платежа
     * @return AbstractConfirmationAttributes Способ подтверждения платежа
     */
    public function getConfirmation();

    /**
     * Проверяет, был ли установлен способ подтверждения платежа
     * @return bool True если способ подтверждения платежа был установлен, false если нет
     */
    public function hasConfirmation();

    /**
     * Устанавливает способ подтверждения платежа
     * @param AbstractConfirmationAttributes|null $value Способ подтверждения платежа
     */
    public function setConfirmation($value);

    /**
     * Возвращает флаг сохранения платёжных данных
     * @return bool Флаг сохранения платёжных данных
     */
    public function getSavePaymentMethod();

    /**
     * Проверяет, был ли установлен флаг сохранения платёжных данных
     * @return bool True если флыг был установлен, false если нет
     */
    public function hasSavePaymentMethod();

    /**
     * Устанавливает флаг сохранения платёжных данных. Значение true инициирует создание многоразового payment_method.
     * @param bool $value Сохранить платежные данные для последующего использования
     */
    public function setSavePaymentMethod($value);

    /**
     * Возвращает флаг автоматического принятия поступившей оплаты
     * @return bool True если требуется автоматически принять поступившую оплату, false если нет
     */
    public function getCapture();

    /**
     * Проверяет, был ли установлен флаг автоматического приняти поступившей оплаты
     * @return bool True если флаг автоматического принятия оплаты был установлен, false если нет
     */
    public function hasCapture();

    /**
     * Устанавливает флаг автоматического принятия поступившей оплаты
     * @param bool $value Автоматически принять поступившую оплату
     */
    public function setCapture($value);

    /**
     * Возвращает IPv4 или IPv6-адрес покупателя
     * @return string IPv4 или IPv6-адрес покупателя
     */
    public function getClientIp();

    /**
     * Проверяет, был ли установлен IPv4 или IPv6-адрес покупателя
     * @return bool True если IP адрес покупателя был установлен, false если нет
     */
    public function hasClientIp();

    /**
     * Устанавливает IP адрес покупателя
     * @param string $value IPv4 или IPv6-адрес покупателя
     */
    public function setClientIp($value);

    /**
     * Возвращает данные оплаты установленные мерчантом
     * @return Metadata Метаданные привязанные к платежу
     */
    public function getMetadata();

    /**
     * Проверяет, были ли установлены метаданные заказа
     * @return bool True если метаданные были установлены, false если нет
     */
    public function hasMetadata();

    /**
     * Устанавливает метаданные, привязанные к платежу
     * @param Metadata|array|null $value Метаданные платежа, устанавливаемые мерчантом
     */
    public function setMetadata($value);

    /**
     * Возвращает данные длинной записи
     * @return Airline
     */
    public function getAirline();

    /**
     * Проверяет, были ли установлены данные длинной записи
     * @return bool
     */
    public function hasAirline();

    /**
     * Устанавливает данные авиабилетов
     * @param AirlineInterface $value Данные авиабилетов
     */
    public function setAirline($value);

    /**
     * Проверяет наличие данных о распределении денег
     * @return bool
     */
    public function hasTransfers();

    /**
     * Возвращает данные о распределении денег — сколько и в какой магазин нужно перевести.
     * Присутствует, если вы используете решение ЮKassa для платформ.
     * (https://yookassa.ru/developers/special-solutions/checkout-for-platforms/basics)
     *
     * @return TransferInterface[] Данные о распределении денег
     */
    public function getTransfers();

    /**
     * Устанавливает данные о распределении денег — сколько и в какой магазин нужно перевести.
     * Присутствует, если вы используете решение ЮKassa для платформ.
     * (https://yookassa.ru/developers/special-solutions/checkout-for-platforms/basics)
     *
     * @param TransferInterface[]|array|null $value Данные о распределении денег
     */
    public function setTransfers($value);

    /**
     * Возвращает данные о сделке, в составе которой проходит платеж
     * @return PaymentDealInfo Данные о сделке, в составе которой проходит платеж.
     */
    public function getDeal();

    /**
     * Проверяет, были ли установлены данные о сделке
     * @return bool True если данные о сделке были установлены, false если нет
     */
    public function hasDeal();

    /**
     * Устанавливает данные о сделке, в составе которой проходит платеж.
     * @param PaymentDealInfo|array|null $value Данные о сделке, в составе которой проходит платеж
     */
    public function setDeal($value);

    /**
     * Возвращает информацию для проверки операции на мошенничество
     * @return FraudData Информация для проверки операции на мошенничество
     */
    public function getFraudData();

    /**
     * Проверяет, была ли установлена информация для проверки операции на мошенничество
     * @return bool True если информация была установлена, false если нет
     */
    public function hasFraudData();

    /**
     * Устанавливает информацию для проверки операции на мошенничество
     * @param FraudData|array|null $value Информация для проверки операции на мошенничество
     */
    public function setFraudData($value);

    /**
     * Возвращает идентификатор покупателя в вашей системе
     * @return string Идентификатор покупателя в вашей системе
     */
    public function getMerchantCustomerId();

    /**
     * Проверяет, был ли установлен идентификатор покупателя в вашей системе
     * @return bool True если идентификатор покупателя был установлен, false если нет
     */
    public function hasMerchantCustomerId();

    /**
     * Устанавливает идентификатор покупателя в вашей системе
     * @param string $value Идентификатор покупателя в вашей системе, например электронная почта или номер телефона. Не более 200 символов
     */
    public function setMerchantCustomerId($value);
}
