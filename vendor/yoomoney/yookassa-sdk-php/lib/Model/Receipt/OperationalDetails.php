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

use DateTime;
use DateTimeZone;
use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Class OperationalDetails
 *
 * Данные операционного реквизита чека
 *
 * @package YooKassa
 *
 * @property string $operationId Идентификатор операции (тег в 54 ФЗ — 1271)
 * @property string $operation_id Идентификатор операции (тег в 54 ФЗ — 1271)
 * @property DateTime $createdAt Время создания операции (тег в 54 ФЗ — 1273)
 * @property DateTime $created_at Время создания операции (тег в 54 ФЗ — 1273)
 * @property string $value Данные операции (тег в 54 ФЗ — 1272)
 */
class OperationalDetails extends AbstractObject
{
    /** @var int Максимальная длинна номера операции */
    const OPERATION_ID_MAX_LENGTH = 256;
    /** @var int Максимальная длинна значение операционного реквизита */
    const VALUE_MAX_LENGTH = 64;

    /**
     * @var string Идентификатор операции (тег в 54 ФЗ — 1271). Число от 0 до 255
     */
    private $_operationId;

    /**
     * @var DateTime Время создания операции (тег в 54 ФЗ — 1273).
     * Указывается по [UTC](https://ru.wikipedia.org/wiki/Всемирное_координированное_время) и передается в формате [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601).
     */
    private $_createdAt;

    /**
     * @var string Данные операции (тег в 54 ФЗ — 1272)
     */
    private $_value;

    /**
     * Возвращает идентификатор операции
     * @return string Идентификатор операции
     */
    public function getOperationId()
    {
        return $this->_operationId;
    }

    /**
     * Устанавливает идентификатор операции
     * @param string $value Идентификатор операции
     */
    public function setOperationId($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty operation_id value', 0, 'OperationalDetails.operationId');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid operation_id value type', 0, 'OperationalDetails.operationId', $value);
        }
        if (mb_strlen((string)$value) > self::OPERATION_ID_MAX_LENGTH) {
            throw new InvalidPropertyValueException('Invalid operation_id value length: "' . $value . '"', 0, 'OperationalDetails.operationId', $value);
        }

        $this->_operationId = (string)$value;

        return $this;
    }

    /**
     * Возвращает время создания операции
     * @return DateTime Время создания операции
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * Устанавливает время создания операции
     * @param string|DateTime $value Время создания операции
     * @throws \Exception
     */
    public function setCreatedAt($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty created_at value', 0, 'OperationalDetails.createdAt');
        }
        if (!TypeCast::canCastToDateTime($value)) {
            throw new InvalidPropertyValueTypeException('Invalid created_at value', 0, 'OperationalDetails.createdAt', $value);
        }
        $dateTime = TypeCast::castToDateTime($value);
        if ($dateTime === null) {
            throw new InvalidPropertyValueException('Invalid created_at value', 0, 'OperationalDetails.createdAt', $value);
        }
        $this->_createdAt = $dateTime;

        return $this;
    }

    /**
     * Возвращает данные операции
     * @return string Данные операции
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Устанавливает данные операции
     * @param string $value Данные операции
     */
    public function setValue($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty value', 0, 'OperationalDetails.value');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid value type', 0, 'OperationalDetails.value', $value);
        }
        if (mb_strlen((string)$value) > self::VALUE_MAX_LENGTH) {
            throw new InvalidPropertyValueException('Invalid value length: "' . $value . '"', 0, 'OperationalDetails.value', $value);
        }

        $this->_value = (string)$value;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $result = parent::jsonSerialize();
        $result['created_at'] = $this->getCreatedAt()
            ->setTimezone(new DateTimeZone('UTC'))
            ->format(YOOKASSA_DATE);

        return $result;
    }
}
