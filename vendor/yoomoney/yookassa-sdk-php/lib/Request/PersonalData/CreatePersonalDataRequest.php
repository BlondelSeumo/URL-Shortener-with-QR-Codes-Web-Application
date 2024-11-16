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

use YooKassa\Common\AbstractRequest;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\Metadata;
use YooKassa\Model\PersonalData\PersonalDataType;

/**
 * Класс, представляющий модель CreatePersonalDataRequest.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 *
 * @property string $type Тип персональных данных
 * @property string $lastName Фамилия пользователя
 * @property string $last_name Фамилия пользователя
 * @property string $firstName Имя пользователя
 * @property string $first_name Имя пользователя
 * @property string $middleName Отчество пользователя
 * @property string $middle_name Отчество пользователя
 * @property Metadata $metadata Метаданные персональных данных указанные мерчантом
 */
class CreatePersonalDataRequest extends AbstractRequest implements CreatePersonalDataRequestInterface
{
    /** Максимальная длина строки фамилии или отчества */
    const MAX_LENGTH_LAST_NAME = 200;
    /** Максимальная длина строки имени */
    const MAX_LENGTH_FIRST_NAME = 100;

    /**
     * @var string Тип персональных данных — цель, для которой вы будете использовать данные.
     */
    private $_type;

    /**
     * @var string Фамилия пользователя.
     */
    private $_last_name;

    /**
     * @var string Имя пользователя.
     */
    private $_first_name;

    /**
     * @var string|null Отчество пользователя. Обязательный параметр, если есть в паспорте.
     */
    private $_middle_name;

    /**
     * Любые дополнительные данные, которые нужны вам для работы (например, ваш внутренний идентификатор заказа).
     *
     * Передаются в виде набора пар «ключ-значение» и возвращаются в ответе от ЮKassa.
     * Ограничения: максимум 16 ключей, имя ключа не больше 32 символов, значение ключа не больше 512 символов, тип данных — строка в формате UTF-8.
     *
     * @var Metadata|null
     */
    private $_metadata;

    /**
     * Возвращает тип персональных данных.
     *
     * @return string Тип персональных данных
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Устанавливает тип персональных данных.
     *
     * @param string $type Тип персональных данных
     *
     * @return $this
     */
    public function setType($type)
    {
        if (!TypeCast::canCastToEnumString($type)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid PersonalData type value type',
                0,
                'SbpPayoutRecipientPersonalDataRequest.type',
                $type
            );
        }
        if (!PersonalDataType::valueExists((string)$type)) {
            throw new InvalidPropertyValueException('Invalid PersonalData type value', 0, 'SbpPayoutRecipientPersonalDataRequest.type', $type);
        }
        $this->_type = (string)$type;

        return $this;
    }

    /**
     * Проверяет наличие типа персональных данных в запросе
     * @return bool True если тип персональных данных задан, false если нет
     */
    public function hasType()
    {
        return !empty($this->_type);
    }

    /**
     * Возвращает фамилию пользователя.
     *
     * @return string Фамилия пользователя
     */
    public function getLastName()
    {
        return $this->_last_name;
    }

    /**
     * Устанавливает фамилию пользователя.
     *
     * @param string $last_name Фамилия пользователя.
     *
     * @return $this
     */
    public function setLastName($last_name)
    {
        if ($last_name === null || $last_name === '') {
            throw new InvalidPropertyValueException(
                'The value of the description parameter is empty',
                0,
                'SbpPayoutRecipientPersonalDataRequest.last_name',
                $last_name
            );
        }
        if (!TypeCast::canCastToString($last_name)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid last_name value type',
                0,
                'SbpPayoutRecipientPersonalDataRequest.last_name',
                $last_name
            );
        }
        $length = mb_strlen((string)$last_name, 'utf-8');
        if ($length > self::MAX_LENGTH_LAST_NAME) {
            throw new InvalidPropertyValueException(
                'The value of the last_name parameter is too long. Max length is ' . self::MAX_LENGTH_LAST_NAME,
                0,
                'SbpPayoutRecipientPersonalDataRequest.last_name',
                $last_name
            );
        }
        $this->_last_name = (string)$last_name;

        return $this;
    }

    /**
     * Проверяет наличие фамилии пользователя в запросе
     * @return bool True если фамилия пользователя задана, false если нет
     */
    public function hasLastName()
    {
        return !empty($this->_last_name);
    }

    /**
     * Возвращает имя пользователя.
     *
     * @return string Имя пользователя
     */
    public function getFirstName()
    {
        return $this->_first_name;
    }

    /**
     * Устанавливает имя пользователя.
     *
     * @param string $first_name Имя пользователя.
     *
     * @return $this
     */
    public function setFirstName($first_name)
    {
        if ($first_name === null || $first_name === '') {
            throw new InvalidPropertyValueException(
                'The value of the description parameter is empty',
                0,
                'SbpPayoutRecipientPersonalDataRequest.first_name',
                $first_name
            );
        }
        if (!TypeCast::canCastToString($first_name)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid first_name value type',
                0,
                'SbpPayoutRecipientPersonalDataRequest.first_name',
                $first_name
            );
        }
        $length = mb_strlen((string)$first_name, 'utf-8');
        if ($length > self::MAX_LENGTH_FIRST_NAME) {
            throw new InvalidPropertyValueException(
                'The value of the first_name parameter is too long. Max length is ' . self::MAX_LENGTH_FIRST_NAME,
                0,
                'SbpPayoutRecipientPersonalDataRequest.first_name',
                $first_name
            );
        }
        $this->_first_name = (string)$first_name;

        return $this;
    }

    /**
     * Проверяет наличие имени пользователя в запросе
     * @return bool True если имя пользователя задано, false если нет
     */
    public function hasFirstName()
    {
        return !empty($this->_first_name);
    }

    /**
     * Возвращает отчество пользователя.
     *
     * @return string|null Отчество пользователя
     */
    public function getMiddleName()
    {
        return $this->_middle_name;
    }

    /**
     * Устанавливает отчество пользователя.
     *
     * @param string|null $middle_name Отчество пользователя
     *
     * @return $this
     */
    public function setMiddleName($middle_name = null)
    {
        if ($middle_name === null || $middle_name === '') {
            $this->_middle_name = null;
            return $this;
        }
        if (!TypeCast::canCastToString($middle_name)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid middle_name value type',
                0,
                'SbpPayoutRecipientPersonalDataRequest.middle_name',
                $middle_name
            );
        }
        $length = mb_strlen((string)$middle_name, 'utf-8');
        if ($length > self::MAX_LENGTH_LAST_NAME) {
            throw new InvalidPropertyValueException(
                'The value of the middle_name parameter is too long. Max length is ' . self::MAX_LENGTH_LAST_NAME,
                0,
                'SbpPayoutRecipientPersonalDataRequest.middle_name',
                $middle_name
            );
        }
        $this->_middle_name = (string)$middle_name;

        return $this;
    }

    /**
     * Проверяет наличие отчества пользователя в запросе
     * @return bool True если отчество пользователя задано, false если нет
     */
    public function hasMiddleName()
    {
        return !empty($this->_middle_name);
    }

    /**
     * Возвращает метаданные.
     *
     * @return Metadata Метаданные
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }

    /**
     * Устанавливает метаданные.
     *
     * @param Metadata|array|null $metadata Метаданные
     *
     * @return $this
     */
    public function setMetadata($metadata = null)
    {
        if ($metadata === null || (is_array($metadata) && empty($metadata))) {
            $this->_metadata = null;
        } elseif ($metadata instanceof Metadata) {
            $this->_metadata = $metadata;
        } elseif (is_array($metadata)) {
            $this->_metadata = new Metadata($metadata);
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid metadata value type',
                0,
                'SbpPayoutRecipientPersonalDataRequest.metadata',
                $metadata
            );
        }

        return $this;
    }

    /**
     * Проверяет, были ли установлены метаданные
     * @return bool True если метаданные были установлены, false если нет
     */
    public function hasMetadata()
    {
        return !empty($this->_metadata) && $this->_metadata->count() > 0;
    }

    /**
     * Проверяет на валидность текущий объект
     * @return bool True если объект запроса валиден, false если нет
     */
    public function validate()
    {
        if (!$this->hasType()) {
            $this->setValidationError('PersonalData type not specified');
            return false;
        }
        if (!$this->hasLastName()) {
            $this->setValidationError('PersonalData last_name not specified');
            return false;
        }
        if (!$this->hasFirstName()) {
            $this->setValidationError('PersonalData first_name not specified');
            return false;
        }

        return true;
    }

    /**
     * Возвращает билдер объектов запросов создания платежа
     * @return CreatePersonalDataRequestBuilder Инстанс билдера объектов запросов
     */
    public static function builder()
    {
        return new CreatePersonalDataRequestBuilder();
    }
}
