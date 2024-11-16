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
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;

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
 * @property bool $test Идентификатор самозанятого в ЮKassa.
 */
interface SelfEmployedInterface
{
    /** @var int Минимальная длина идентификатора */
    const MIN_LENGTH_ID = 36;
    /** @var int Максимальная длина идентификатора */
    const MAX_LENGTH_ID = 50;

    /**
     * Возвращает идентификатор самозанятого.
     *
     * @return string
     */
    public function getId();

    /**
     * Устанавливает идентификатор самозанятого.
     *
     * @param string $id Идентификатор самозанятого в ЮKassa
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Возвращает статус самозанятого.
     *
     * @return string Статус самозанятого
     */
    public function getStatus();

    /**
     * Устанавливает статус самозанятого.
     *
     * @param string $status Статус самозанятого
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * Возвращает время создания объекта самозанятого
     *
     * @return DateTime Время создания объекта самозанятого
     */
    public function getCreatedAt();

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
    public function setCreatedAt($created_at);

    /**
     * Возвращает ИНН самозанятого
     *
     * @return string|null ИНН самозанятого
     */
    public function getItn();

    /**
     * Устанавливает ИНН самозанятого
     *
     * @param string|null $itn ИНН самозанятого
     *
     * @return $this
     */
    public function setItn($itn = null);

    /**
     * Возвращает телефон самозанятого.
     *
     * @return string|null Телефон самозанятого
     */
    public function getPhone();

    /**
     * Устанавливает телефон самозанятого.
     *
     * @param string|null $phone Телефон самозанятого
     *
     * @return $this
     */
    public function setPhone($phone = null);

    /**
     * Возвращает сценарий подтверждения.
     *
     * @return SelfEmployedConfirmation|null Сценарий подтверждения
     */
    public function getConfirmation();

    /**
     * Устанавливает сценарий подтверждения.
     *
     * @param SelfEmployedConfirmation|array|null $confirmation Сценарий подтверждения
     *
     * @return $this
     */
    public function setConfirmation($confirmation = null);

    /**
     * Возвращает признак тестовой операции.
     *
     * @return bool Признак тестовой операции.
     */
    public function getTest();

    /**
     * Устанавливает признак тестовой операции.
     *
     * @param bool $test Признак тестовой операции.
     *
     * @return $this
     */
    public function setTest($test);
}
