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

namespace YooKassa\Request\SelfEmployed;

use YooKassa\Common\AbstractRequest;
use YooKassa\Common\AbstractRequestBuilder;
use YooKassa\Common\Exceptions\InvalidRequestException;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmation;

/**
 * Класс билдера объектов запросов к API на создание самозанятого
 *
 * @todo @example 02-builder.php 11 78 Пример использования билдера
 *
 * @package YooKassa
 */
class SelfEmployedRequestBuilder extends AbstractRequestBuilder
{
    /**
     * Собираемый объект запроса
     * @var SelfEmployedRequest
     */
    protected $currentObject;

    /**
     * Инициализирует объект запроса, который в дальнейшем будет собираться билдером
     * @return SelfEmployedRequest Инстанс собираемого объекта запроса к API
     */
    protected function initCurrentObject()
    {
        return new SelfEmployedRequest();
    }

    /**
     * Устанавливает ИНН самозанятого
     *
     * @param string|null $value ИНН самозанятого
     *
     * @return self Инстанс билдера запросов
     */
    public function setItn($value)
    {
        $this->currentObject->setItn($value);

        return $this;
    }

    /**
     * Устанавливает телефон самозанятого.
     *
     * @param string|null $value Телефон самозанятого.
     *
     * @return self Инстанс билдера запросов
     */
    public function setPhone($value)
    {
        $this->currentObject->setPhone($value);

        return $this;
    }

    /**
     * Устанавливает сценарий подтверждения.
     *
     * @param SelfEmployedConfirmation|array|null $value Сценарий подтверждения.
     *
     * @return self Инстанс билдера запросов
     */
    public function setConfirmation($value)
    {
        $this->currentObject->setConfirmation($value);

        return $this;
    }

    /**
     * Строит и возвращает объект запроса для отправки в API ЮKassa
     * @param array|null $options Массив параметров для установки в объект запроса
     * @return SelfEmployedRequest|AbstractRequest Инстанс объекта запроса
     *
     * @throws InvalidRequestException Выбрасывается если собрать объект запроса не удалось
     */
    public function build(array $options = null)
    {
        return parent::build($options);
    }
}
