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

namespace YooKassa\Model\SelfEmployed;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Класс, представляющий модель SelfEmployedConfirmation.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 *
 * @property string $type Тип сценария подтверждения.
 */
class SelfEmployedConfirmation extends AbstractObject
{
    /**
     * Тип сценария подтверждения
     * @var string
     */
    private $_type;

    /**
     * Возвращает тип сценария подтверждения
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Устанавливает тип сценария подтверждения
     * @param string $value
     */
    protected function setType($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty value for "type" parameter in SelfEmployedConfirmation',
                0,
                'confirmation.type'
            );
        }
        if (!TypeCast::canCastToEnumString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "type" parameter in SelfEmployedConfirmation',
                0,
                'confirmation.type',
                $value
            );
        }
        if (!SelfEmployedConfirmationType::valueExists($value)) {
            throw new InvalidPropertyValueException(
                'Invalid value for "type" parameter in SelfEmployedConfirmation',
                0,
                'confirmation.type',
                $value
            );
        }
        $this->_type = (string)$value;
    }
}
