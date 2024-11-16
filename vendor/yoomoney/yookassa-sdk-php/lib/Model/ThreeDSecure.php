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

namespace YooKassa\Model;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * ThreeDSecure - Данные о прохождении пользователем аутентификации по 3‑D Secure для подтверждения платежа.
 *
 * @property bool $applied Отображение пользователю формы для прохождения аутентификации по 3‑D Secure.
 */
class ThreeDSecure extends AbstractObject
{
    /**
     * @var bool|null Отображение пользователю формы для прохождения аутентификации по 3‑D Secure.
     */
    private $_applied;

    /**
     * Возвращает признак отображения пользователю формы для прохождения аутентификации по 3‑D Secure
     *
     * @return bool|null Признак отображения пользователю формы для прохождения аутентификации по 3‑D Secure
     */
    public function getApplied()
    {
        return $this->_applied;
    }

    /**
     * Устанавливает признак отображения пользователю формы для прохождения аутентификации по 3‑D Secure
     *
     * @param bool $value Данные о прохождении аутентификации по 3‑D Secure
     *
     * @throws InvalidPropertyValueTypeException
     */
    public function setApplied($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty value for "applied" parameter in ThreeDSecure',
                0,
                'authorization_details.three_d_secure.applied'
            );
        }

        if (!TypeCast::canCastToBoolean($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid applied value type',
                0,
                'authorization_details.three_d_secure.applied',
                $value
            );
        }

        $this->_applied = (bool)$value;
    }
}
