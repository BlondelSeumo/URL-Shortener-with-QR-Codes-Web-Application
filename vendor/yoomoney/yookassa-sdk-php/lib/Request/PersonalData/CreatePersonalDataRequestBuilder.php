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

namespace YooKassa\Request\PersonalData;

use YooKassa\Common\AbstractPaymentRequestBuilder;
use YooKassa\Common\AbstractRequest;
use YooKassa\Common\AbstractRequestBuilder;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Common\Exceptions\InvalidRequestException;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\Metadata;
use YooKassa\Model\Payout\AbstractPayoutDestination;
use YooKassa\Model\Deal\PayoutDealInfo;

/**
 * Класс билдера объектов запросов к API на создание платежа
 *
 * @example 02-builder.php 11 78 Пример использования билдера
 *
 * @package YooKassa
 */
class CreatePersonalDataRequestBuilder extends AbstractRequestBuilder
{
    /**
     * Собираемый объект запроса
     * @var CreatePersonalDataRequest
     */
    protected $currentObject;

    /**
     * Инициализирует объект запроса, который в дальнейшем будет собираться билдером
     * @return CreatePersonalDataRequest Инстанс собираемого объекта запроса к API
     */
    protected function initCurrentObject()
    {
        return new CreatePersonalDataRequest();
    }

    /**
     * Устанавливает тип персональных данных.
     *
     * @param string $value Тип персональных данных
     *
     * @return self Инстанс билдера запросов
     */
    public function setType($value)
    {
        $this->currentObject->setType($value);

        return $this;
    }

    /**
     * Устанавливает фамилию пользователя.
     *
     * @param string $value Фамилия пользователя.
     *
     * @return self
     */
    public function setLastName($value)
    {
        $this->currentObject->setLastName($value);

        return $this;
    }

    /**
     * Устанавливает имя пользователя.
     *
     * @param string $value Имя пользователя.
     *
     * @return self
     */
    public function setFirstName($value)
    {
        $this->currentObject->setFirstName($value);

        return $this;
    }

    /**
     * Устанавливает отчество пользователя.
     *
     * @param string $value Отчество пользователя.
     *
     * @return self
     */
    public function setMiddleName($value)
    {
        $this->currentObject->setMiddleName($value);

        return $this;
    }

    /**
     * Устанавливает метаданные, привязанные к платежу
     * @param Metadata|array|null $value Метаданные платежа, устанавливаемые мерчантом
     * @return CreatePersonalDataRequestBuilder Инстанс текущего билдера
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если переданные данные не удалось интерпретировать как
     * метаданные платежа
     */
    public function setMetadata($value)
    {
        $this->currentObject->setMetadata($value);
        return $this;
    }

    /**
     * Строит и возвращает объект запроса для отправки в API ЮKassa
     * @param array|null $options Массив параметров для установки в объект запроса
     * @return CreatePersonalDataRequestInterface|CreatePersonalDataRequest|AbstractRequest Инстанс объекта запроса
     *
     * @throws InvalidRequestException Выбрасывается если собрать объект запроса не удалось
     */
    public function build(array $options = null)
    {
        return parent::build($options);
    }
}
