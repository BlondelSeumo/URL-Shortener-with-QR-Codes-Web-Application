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
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmation;

/**
 * Класс, представляющий модель SelfEmployedRequest.
 *
 * Запрос на создание объекта самозанятого.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 * @property string|null $itn ИНН самозанятого.
 * @property string|null $phone Телефон самозанятого, который привязан к личному кабинету в сервисе Мой налог.
 * @property SelfEmployedRequestConfirmation|null $confirmation Сценарий подтверждения пользователем заявки ЮMoney на получение прав для регистрации чеков в сервисе Мой налог.
 */
class SelfEmployedRequest extends AbstractRequest
{
    /**
     * @var string|null
     */
    private $_itn;

    /**
     * @var string|null
     */
    private $_phone;

    /**
     * @var SelfEmployedRequestConfirmation|null
     */
    private $_confirmation;

    /**
     * Возвращает ИНН самозанятого
     *
     * @return string|null ИНН самозанятого
     */
    public function getItn()
    {
        return $this->_itn;
    }

    /**
     * Устанавливает ИНН самозанятого
     *
     * @param string|null $itn ИНН самозанятого
     *
     * @return $this
     */
    public function setItn($itn = null)
    {
        if ($itn === null || $itn === '') {
            $this->_itn = null;
        } elseif (!TypeCast::canCastToString($itn)) {
            throw new InvalidPropertyValueTypeException('Invalid itn value type', 0, 'SelfEmployed.itn');
        } else {
            $this->_itn = (string)preg_replace('/\D/', '', $itn);
        }

        return $this;
    }

    /**
     * Проверяет наличие ИНН самозанятого в запросе
     * @return bool True если ИНН самозанятого задан, false если нет
     */
    public function hasItn()
    {
        return !empty($this->_itn);
    }

    /**
     * Возвращает телефон самозанятого.
     *
     * @return string|null Телефон самозанятого
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * Устанавливает телефон самозанятого.
     *
     * @param string|null $phone Телефон самозанятого
     *
     * @return $this
     */
    public function setPhone($phone = null)
    {
        if ($phone === null || $phone === '') {
            $this->_phone = null;
        } elseif (!TypeCast::canCastToString($phone)) {
            throw new InvalidPropertyValueTypeException('Invalid phone value type', 0, 'SelfEmployedRequest.phone');
        } else {
            $this->_phone = (string)preg_replace('/\D/', '', $phone);
        }

        return $this;
    }

    /**
     * Проверяет наличие телефона самозанятого в запросе
     * @return bool True если телефон самозанятого задан, false если нет
     */
    public function hasPhone()
    {
        return !empty($this->_phone);
    }

    /**
     * Возвращает сценарий подтверждения.
     *
     * @return SelfEmployedRequestConfirmation|null Сценарий подтверждения
     */
    public function getConfirmation()
    {
        return $this->_confirmation;
    }

    /**
     * Устанавливает сценарий подтверждения.
     *
     * @param SelfEmployedConfirmation|array|null $confirmation Сценарий подтверждения
     *
     * @return $this
     */
    public function setConfirmation($confirmation = null)
    {
        if ($confirmation === null || $confirmation === '') {
            $this->_confirmation = null;
        } elseif (is_array($confirmation)) {
            $factory = new SelfEmployedRequestConfirmationFactory();
            $this->_confirmation = $factory->factoryFromArray($confirmation);
        } elseif ($confirmation instanceof SelfEmployedRequestConfirmation) {
            $this->_confirmation = $confirmation;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "confirmation" parameter in SelfEmployedRequest',
                0,
                'SelfEmployedRequest.confirmation',
                $confirmation
            );
        }

        return $this;
    }

    /**
     * Проверяет наличие сценария подтверждения самозанятого в запросе
     * @return bool True если сценарий подтверждения самозанятого задан, false если нет
     */
    public function hasConfirmation()
    {
        return !empty($this->_confirmation);
    }

    /**
     * Проверяет на валидность текущий объект
     * @return bool True если объект запроса валиден, false если нет
     */
    public function validate()
    {
        if (!$this->hasPhone() && !$this->hasItn()) {
            $this->setValidationError('Both itn and phone values are empty in SelfEmployedRequest');
            return false;
        }

        return true;
    }

    /**
     * Возвращает билдер объектов запросов создания платежа
     * @return SelfEmployedRequestBuilder Инстанс билдера объектов запросов
     */
    public static function builder()
    {
        return new SelfEmployedRequestBuilder();
    }
}
