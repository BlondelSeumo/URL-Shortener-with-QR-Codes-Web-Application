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
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Класс, представляющий модель FraudData.
 *
 * Информация для проверки операции на мошенничество
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 *
 * @property string $toppedUpPhone Номер телефона для пополнения
 * @property string $topped_up_phone Номер телефона для пополнения
 */
class FraudData extends AbstractObject
{
    /**
     * Номер телефона для пополнения. Не более 15 символов. Пример: ~`79110000000`.
     * Необходим при [пополнении баланса телефона](/developers/payment-acceptance/scenario-extensions/top-up-phones-balance).
     *
     * @var string|null
     */
    private $_topped_up_phone;

    /**
     * Возвращает номер телефона для пополнения.
     *
     * @return string|null Номер телефона для пополнения
     */
    public function getToppedUpPhone()
    {
        return $this->_topped_up_phone;
    }

    /**
     * Устанавливает номер телефона для пополнения.
     *
     * @param string|null $topped_up_phone Номер телефона для пополнения
     *
     * @return FraudData
     */
    public function setToppedUpPhone($topped_up_phone = null)
    {
        if ($topped_up_phone === null || $topped_up_phone === '') {
            $this->_topped_up_phone = null;
        } elseif (TypeCast::canCastToString($topped_up_phone)) {
            $value = preg_replace('/\D/', '', $topped_up_phone);
            if (mb_strlen($value, 'utf-8') > 15) {
                throw new InvalidPropertyValueException(
                    'Invalid FraudData topped_up_phone value',
                    0,
                    'FraudData.topped_up_phone',
                    $value
                );
            }
            $this->_topped_up_phone = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid topped_up_phone topped_up_phone type in FraudData',
                0,
                'FraudData.topped_up_phone',
                $topped_up_phone
            );
        }

        return $this;
    }
}
