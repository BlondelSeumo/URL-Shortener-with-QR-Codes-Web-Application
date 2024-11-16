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

use YooKassa\Model\Metadata;

/**
 * Класс, представляющий интерфейс SbpPayoutRecipientPersonalDataRequestInterface.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 */
interface CreatePersonalDataRequestInterface
{
    /**
     * Возвращает тип персональных данных.
     *
     * @return string Тип персональных данных
     */
    public function getType();

    /**
     * Устанавливает тип персональных данных.
     *
     * @param string $type Тип персональных данных
     *
     * @return $this
     */
    public function setType($type);

    /**
     * Проверяет наличие типа персональных данных в запросе
     * @return bool True если тип персональных данных задан, false если нет
     */
    public function hasType();

    /**
     * Возвращает фамилию пользователя.
     *
     * @return string Фамилия пользователя
     */
    public function getLastName();

    /**
     * Устанавливает фамилию пользователя.
     *
     * @param string $last_name Фамилия пользователя.
     *
     * @return $this
     */
    public function setLastName($last_name);

    /**
     * Проверяет наличие фамилии пользователя в запросе
     * @return bool True если фамилия пользователя задана, false если нет
     */
    public function hasLastName();

    /**
     * Возвращает имя пользователя.
     *
     * @return string Имя пользователя
     */
    public function getFirstName();

    /**
     * Устанавливает имя пользователя.
     *
     * @param string $first_name Имя пользователя.
     *
     * @return $this
     */
    public function setFirstName($first_name);

    /**
     * Проверяет наличие имени пользователя в запросе
     * @return bool True если имя пользователя задано, false если нет
     */
    public function hasFirstName();

    /**
     * Возвращает отчество пользователя.
     *
     * @return string|null Отчество пользователя
     */
    public function getMiddleName();

    /**
     * Устанавливает отчество пользователя.
     *
     * @param string|null $middle_name Отчество пользователя
     *
     * @return $this
     */
    public function setMiddleName($middle_name = null);

    /**
     * Проверяет наличие отчества пользователя в запросе
     * @return bool True если отчество пользователя задано, false если нет
     */
    public function hasMiddleName();

    /**
     * Возвращает метаданные.
     *
     * @return Metadata Метаданные
     */
    public function getMetadata();

    /**
     * Устанавливает метаданные.
     *
     * @param Metadata|array|null $metadata Метаданные
     *
     * @return $this
     */
    public function setMetadata($metadata = null);

    /**
     * Проверяет, были ли установлены метаданные
     * @return bool True если метаданные были установлены, false если нет
     */
    public function hasMetadata();

    /**
     * Проверяет на валидность текущий объект
     * @return bool True если объект запроса валиден, false если нет
     */
    public function validate();

    /**
     * Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации
     * @return array Ассоциативный массив со свойствами текущего объекта
     */
    public function toArray();
}
