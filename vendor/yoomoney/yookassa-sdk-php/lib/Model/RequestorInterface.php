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

/**
 * Interface RequestorInterface
 *
 * Инициатор платежа или возврата
 *
 * Инициатором может быть магазин, подключенный к ЮKassa, `merchant` или приложение, которому владелец магазина
 * [разрешил](https://yookassa.ru/developers/partners-api/basics) совершать операции от своего имени `third_party_client`.
 *
 * @package YooKassa
 * @deprecated Не используется. Будет удален в следующих версиях
 */
interface RequestorInterface
{
    /**
     * Возвращает тип инициатора
     * @return string
     */
    public function getType();

    /**
     * Устанавливает тип инициатора
     * @param string $value Тип инициатора
     */
    public function setType($value);

    /**
     * Возвращает идентификатор магазина
     * @return string|null
     */
    public function getAccountId();

    /**
     * Устанавливает идентификатор магазина
     * @param string $value Идентификатор магазина
     */
    public function setAccountId($value);

    /**
     * Возвращает идентификатор приложения
     * @return string|null
     */
    public function getClientId();

    /**
     * Устанавливает идентификатор приложения
     * @param string $value Идентификатор приложения
     */
    public function setClientId($value);

    /**
     * Возвращает название приложения
     * @return string|null
     */
    public function getClientName();

    /**
     * Устанавливает название приложения
     * @param string $value Название приложения
     */
    public function setClientName($value);
}
