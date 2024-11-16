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

namespace YooKassa\Model\Receipt;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Class AdditionalUserProps
 *
 * Дополнительный реквизит пользователя
 *
 * @package YooKassa
 *
 * @property string $name Наименование дополнительного реквизита пользователя (тег в 54 ФЗ — 1085). Не более 64 символов.
 * @property string $value Значение дополнительного реквизита пользователя (тег в 54 ФЗ — 1086). Не более 234 символов.
 */
class AdditionalUserProps extends AbstractObject
{
    /** @var int Максимальная длинна наименования реквизита */
    const NAME_MAX_LENGTH = 64;
    /** @var int Максимальная длинна значение наименования реквизита */
    const VALUE_MAX_LENGTH = 234;

    /**
     * @var string Наименование дополнительного реквизита пользователя (тег в 54 ФЗ — 1085). Не более 64 символов
     */
    private $_name;

    /**
     * @var string Значение дополнительного реквизита пользователя (тег в 54 ФЗ — 1086). Не более 234 символов
     */
    private $_value;

    /**
     * Возвращает наименование дополнительного реквизита пользователя
     * @return string Наименование дополнительного реквизита пользователя
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Устанавливает наименование дополнительного реквизита пользователя
     * @param string $value Наименование дополнительного реквизита пользователя
     */
    public function setName($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty name value', 0, 'AdditionalUserProps.name');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid name value type', 0, 'AdditionalUserProps.name', $value);
        }
        if (mb_strlen((string)$value) > self::NAME_MAX_LENGTH) {
            throw new InvalidPropertyValueException('Invalid name value length: "' . $value . '"', 0, 'AdditionalUserProps.name', $value);
        }

        $this->_name = (string)$value;

        return $this;
    }

    /**
     * Возвращает значение дополнительного реквизита пользователя
     * @return string Значение дополнительного реквизита пользователя
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Устанавливает значение дополнительного реквизита пользователя
     * @param string $value Значение дополнительного реквизита пользователя
     */
    public function setValue($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty value', 0, 'AdditionalUserProps.value');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid value type', 0, 'AdditionalUserProps.value', $value);
        }
        if (mb_strlen((string)$value) > self::VALUE_MAX_LENGTH) {
            throw new InvalidPropertyValueException('Invalid value length: "' . $value . '"', 0, 'AdditionalUserProps.value', $value);
        }

        $this->_value = (string)$value;

        return $this;
    }
}
