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

namespace YooKassa\Request\Deals;

/**
 * Interface DealsRequestInterface
 *
 * @package YooKassa
 *
 * @property string|null $cursor Страница выдачи результатов, которую необходимо отобразить
 * @property integer|null $limit Ограничение количества объектов платежа, отображаемых на одной странице выдачи
 * @property \DateTime|null $createdAtGte Время создания, от (включительно)
 * @property \DateTime|null $createdAtGt Время создания, от (не включая)
 * @property \DateTime|null $createdAtLte Время создания, до (включительно)
 * @property \DateTime|null $createdAtLt Время создания, до (не включая)
 * @property \DateTime|null $expiresAtGte Время автоматического закрытия, от (включительно)
 * @property \DateTime|null $expiresAtGt Время автоматического закрытия, от (не включая)
 * @property \DateTime|null $expiresAtLte Время автоматического закрытия, до (включительно)
 * @property \DateTime|null $expiresAtLt Время автоматического закрытия, до (не включая)
 * @property string|null $full_text_search Фильтр по описанию сделки — параметру description
 * @property string|null $status Статус платежа
 */
interface DealsRequestInterface
{
    /**
     * Возвращает страницу выдачи результатов или null, если она до этого не была установлена
     * @return string|null Страница выдачи результатов
     */
    public function getCursor();

    /**
     * Проверяет, была ли установлена страница выдачи результатов
     * @return bool True если страница выдачи результатов была установлена, false если нет
     */
    public function hasCursor();

    /**
     * Устанавливает страницу выдачи результатов
     * @param string $value Страница
     * @return void
     */
    public function setCursor($value);

    /**
     * Возвращает дату создания от которой будут возвращены сделки или null, если дата не была установлена
     * @return \DateTime|null Время создания, от (включительно)
     */
    public function getCreatedAtGte();

    /**
     * Проверяет, была ли установлена дата создания от которой выбираются сделки
     * @return bool True если дата была установлена, false если нет
     */
    public function hasCreatedAtGte();

    /**
     * Устанавливает дату создания от которой выбираются сделки
     * @param \DateTime $value Дата
     * @return void
     */
    public function setCreatedAtGte($value);

    /**
     * Возвращает дату создания от которой будут возвращены сделки или null, если дата не была установлена
     * @return \DateTime|null Время создания, от (не включая)
     */
    public function getCreatedAtGt();

    /**
     * Проверяет, была ли установлена дата создания от которой выбираются сделки
     * @return bool True если дата была установлена, false если нет
     */
    public function hasCreatedAtGt();

    /**
     * Устанавливает дату создания от которой выбираются сделки
     * @param \DateTime $value Дата
     * @return void
     */
    public function setCreatedAtGt($value);

    /**
     * Возвращает дату создания до которой будут возвращены сделки или null, если дата не была установлена
     * @return \DateTime|null Время создания, до (включительно)
     */
    public function getCreatedAtLte();

    /**
     * Проверяет, была ли установлена дата создания до которой выбираются сделки
     * @return bool True если дата была установлена, false если нет
     */
    public function hasCreatedAtLte();

    /**
     * Устанавливает дату создания до которой выбираются сделки
     * @param \DateTime $value Дата
     * @return void
     */
    public function setCreatedAtLte($value);

    /**
     * Возвращает дату создания до которой будут возвращены сделки или null, если дата не была установлена
     * @return \DateTime|null Время создания, до (не включая)
     */
    public function getCreatedAtLt();

    /**
     * Проверяет, была ли установлена дата создания до которой выбираются сделки
     * @return bool True если дата была установлена, false если нет
     */
    public function hasCreatedAtLt();

    /**
     * Устанавливает дату создания до которой выбираются сделки
     * @param \DateTime $value Дата
     * @return void
     */
    public function setCreatedAtLt($value);

    /**
     * Возвращает дату автоматического закрытия от которой будут возвращены сделки или null, если дата не была установлена
     * @return \DateTime|null Время автоматического закрытия, от (включительно)
     */
    public function getExpiresAtGte();

    /**
     * Проверяет, была ли установлена дата автоматического закрытия от которой выбираются сделки
     * @return bool True если дата была установлена, false если нет
     */
    public function hasExpiresAtGte();

    /**
     * Устанавливает дату автоматического закрытия от которой выбираются сделки
     * @param \DateTime $value Дата
     * @return void
     */
    public function setExpiresAtGte($value);

    /**
     * Возвращает дату автоматического закрытия от которой будут возвращены сделки или null, если дата не была установлена
     * @return \DateTime|null Время автоматического закрытия, от (не включая)
     */
    public function getExpiresAtGt();

    /**
     * Проверяет, была ли установлена дата автоматического закрытия от которой выбираются сделки
     * @return bool True если дата была установлена, false если нет
     */
    public function hasExpiresAtGt();

    /**
     * Устанавливает дату автоматического закрытия от которой выбираются сделки
     * @param \DateTime $value Дата автоматического закрытия
     * @return void
     */
    public function setExpiresAtGt($value);

    /**
     * Возвращает дату автоматического закрытия до которой будут возвращены сделки или null, если дата не была установлена
     * @return \DateTime|null Время автоматического закрытия, до (включительно)
     */
    public function getExpiresAtLte();

    /**
     * Проверяет, была ли установлена дата автоматического закрытия до которой выбираются сделки
     * @return bool True если дата была установлена, false если нет
     */
    public function hasExpiresAtLte();

    /**
     * Устанавливает дату автоматического закрытия до которой выбираются сделки
     * @param \DateTime $value Дата автоматического закрытия
     * @return void
     */
    public function setExpiresAtLte($value);

    /**
     * Возвращает дату автоматического закрытия до которой будут возвращены сделки или null, если дата не была установлена
     * @return \DateTime|null Время автоматического закрытия, до (не включая)
     */
    public function getExpiresAtLt();

    /**
     * Проверяет, была ли установлена автоматического закрытия до которой выбираются сделки
     * @return bool True если дата была установлена, false если нет
     */
    public function hasExpiresAtLt();

    /**
     * Устанавливает дату автоматического закрытия до которой выбираются сделки
     * @param \DateTime $value Дата автоматического закрытия
     * @return void
     */
    public function setExpiresAtLt($value);

    /**
     * Возвращает ограничение количества объектов сделок или null, если оно до этого не было установлено
     * @return string|null Ограничение количества объектов сделок
     */
    public function getLimit();

    /**
     * Проверяет, было ли установлено ограничение количества объектов сделок
     * @return bool True если ограничение количества объектов сделок было установлено, false если нет
     */
    public function hasLimit();

    /**
     * Устанавливает ограничение количества объектов сделок
     * @param int $value Количества объектов сделок на странице
     * @return void
     */
    public function setLimit($value);

    /**
     * Возвращает статус выбираемых сделок или null, если он до этого не был установлен
     * @return string|null Статус выбираемых сделок
     */
    public function getStatus();

    /**
     * Проверяет, был ли установлен статус выбираемых сделок
     * @return bool True если статус был установлен, false если нет
     */
    public function hasStatus();

    /**
     * Устанавливает статус выбираемых сделок
     * @param string $value Статус сделок
     * @return void
     */
    public function setStatus($value);

    /**
     * Возвращает фильтр по описанию сделки или null, если он до этого не был установлен
     * @return string|null Фильтр по описанию сделки
     */
    public function getFullTextSearch();

    /**
     * Проверяет, был ли установлен фильтр по описанию сделки
     * @return bool True если фильтр по описанию сделки был установлен, false если нет
     */
    public function hasFullTextSearch();

    /**
     * Устанавливает фильтр по описанию сделки
     * @param string $value Фильтр по описанию сделки
     * @return void
     */
    public function setFullTextSearch($value);
}
