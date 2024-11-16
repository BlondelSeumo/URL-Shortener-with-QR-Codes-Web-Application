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

use DateTime;
use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Класс, представляющий модель SelfEmployed.
 *
 * Объект самозанятого.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 *
 * @property string $id Идентификатор самозанятого в ЮKassa.
 * @property string $status Статус подключения самозанятого и выдачи ЮMoney прав на регистрацию чеков.
 * @property DateTime $created_at Время создания объекта самозанятого.
 * @property DateTime $createdAt Время создания объекта самозанятого.
 * @property string|null $itn ИНН самозанятого.
 * @property string|null $phone Телефон самозанятого, который привязан к личному кабинету в сервисе Мой налог.
 * @property SelfEmployedConfirmation|null $confirmation Сценарий подтверждения пользователем заявки ЮMoney на получение прав для регистрации чеков в сервисе Мой налог.
 * @property bool $test Признак тестовой операции.
 */
class SelfEmployed extends AbstractObject implements SelfEmployedInterface
{
    /**
     * @var string Идентификатор самозанятого в ЮKassa.
     */
    private $_id;

    /**
     * @var string Статус подключения самозанятого и выдачи ЮMoney прав на регистрацию чеков.
     */
    private $_status;

    /**
     * Время создания объекта самозанятого.
     *
     * Указывается по [UTC](https://ru.wikipedia.org/wiki/Всемирное_координированное_время) и передается в формате [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601).
     * Пример: ~`2017-11-03T11:52:31.827Z`
     *
     * @var DateTime
     */
    private $_created_at;

    /**
     * @var string|null ИНН самозанятого.
     */
    private $_itn;

    /**
     * @var string|null Телефон самозанятого, который привязан к личному кабинету в сервисе Мой налог.
     */
    private $_phone;

    /**
     * Сценарий подтверждения пользователем заявки ЮMoney на получение прав для регистрации чеков в сервисе Мой налог.
     *
     * @var SelfEmployedConfirmation|null
     */
    private $_confirmation;

    /**
     * @var bool Признак тестовой операции.
     */
    private $_test;

    /**
     * Возвращает id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Устанавливает идентификатор самозанятого.
     *
     * @param string $id Идентификатор самозанятого в ЮKassa.
     *
     * @return $this
     */
    public function setId($id)
    {
        if (!TypeCast::canCastToString($id)) {
            throw new InvalidPropertyValueTypeException('Invalid SelfEmployed id value type', 0, 'SelfEmployed.id', $id);
        }
        $length = mb_strlen($id, 'utf-8');
        if ($length < SelfEmployedInterface::MIN_LENGTH_ID || $length > SelfEmployedInterface::MAX_LENGTH_ID) {
            throw new InvalidPropertyValueException('Invalid SelfEmployed id value', 0, 'SelfEmployed.id', $id);
        }
        $this->_id = (string)$id;

        return $this;
    }

    /**
     * Возвращает статус самозанятого.
     *
     * @return string Статус самозанятого
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Устанавливает статус самозанятого.
     *
     * @param string $status Статус самозанятого
     *
     * @return $this
     */
    public function setStatus($status)
    {
        if (!TypeCast::canCastToEnumString($status)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid SelfEmployedS status value type',
                0,
                'SelfEmployedS.status',
                $status
            );
        }
        if (!SelfEmployedStatus::valueExists((string)$status)) {
            throw new InvalidPropertyValueException('Invalid SelfEmployedS status value', 0, 'SelfEmployedS.status', $status);
        }
        $this->_status = (string)$status;

        return $this;
    }

    /**
     * Возвращает время создания объекта самозанятого
     *
     * @return DateTime Время создания объекта самозанятого
     */
    public function getCreatedAt()
    {
        return $this->_created_at;
    }

    /**
     * Устанавливает время создания объекта самозанятого
     *
     * @param DateTime|string|int $created_at Время создания объекта самозанятого
     *
     * @return $this
     *
     * @throws EmptyPropertyValueException Выбрасывается если в метод была передана пустая дата
     * @throws InvalidPropertyValueException Выбрасывается если передали строку, которую не удалось привести к дате
     * @throws InvalidPropertyValueTypeException|\Exception Выбрасывается если был передан аргумент, который невозможно
     * интерпретировать как дату или время
     */
    public function setCreatedAt($created_at)
    {
        if ($created_at === null || $created_at === '') {
            throw new EmptyPropertyValueException('Empty created_at value', 0, 'SelfEmployed.createdAt');
        }
        if (!TypeCast::canCastToDateTime($created_at)) {
            throw new InvalidPropertyValueTypeException('Invalid created_at value', 0, 'SelfEmployed.createdAt', $created_at);
        }
        $dateTime = TypeCast::castToDateTime($created_at);
        if ($dateTime === null) {
            throw new InvalidPropertyValueException('Invalid created_at value', 0, 'SelfEmployed.createdAt', $created_at);
        }
        $this->_created_at = $dateTime;

        return $this;
    }

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
            throw new InvalidPropertyValueTypeException('Invalid phone value type', 0, 'SelfEmployed.phone');
        } else {
            $this->_phone = (string)preg_replace('/\D/', '', $phone);
        }

        return $this;
    }

    /**
     * Возвращает сценарий подтверждения.
     *
     * @return SelfEmployedConfirmation|null Сценарий подтверждения
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
            $factory = new SelfEmployedConfirmationFactory();
            $this->_confirmation = $factory->factoryFromArray($confirmation);
        } elseif ($confirmation instanceof SelfEmployedConfirmation) {
            $this->_confirmation = $confirmation;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "confirmation" parameter in SelfEmployed',
                0,
                'SelfEmployed.confirmation',
                $confirmation
            );
        }

        return $this;
    }

    /**
     * Возвращает признак тестовой операции.
     *
     * @return bool Признак тестовой операции.
     */
    public function getTest()
    {
        return $this->_test;
    }

    /**
     * Устанавливает признак тестовой операции.
     *
     * @param bool $test Признак тестовой операции.
     *
     * @return $this
     */
    public function setTest($test)
    {
        if ($test === null || $test === '') {
            throw new EmptyPropertyValueException('Empty SelfEmployed test flag value', 0, 'SelfEmployed.test');
        }
        if (!TypeCast::canCastToBoolean($test)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid SelfEmployed test flag value type',
                0,
                'SelfEmployed.test',
                $test
            );
        }
        $this->_test = (bool)$test;

        return $this;
    }
}
