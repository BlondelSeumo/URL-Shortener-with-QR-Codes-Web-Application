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

namespace YooKassa\Model\PersonalData;

use DateTime;
use YooKassa\Model\Metadata;

/**
 * Класс, представляющий модель PersonalDataInterface.
 *
 * Информация о персональных данных
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 */
interface PersonalDataInterface
{
    /** @var int Минимальная длина идентификатора */
    const MIN_LENGTH_ID = 36;
    /** @var int Максимальная длина идентификатора */
    const MAX_LENGTH_ID = 50;

    /**
     * Возвращает id.
     *
     * @return string
     */
    public function getId();

    /**
     * Устанавливает id.
     *
     * @param string $id Идентификатор персональных данных, сохраненных в ЮKassa.
     *
     * @return $this
     */
    public function setId($id);

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
     * Возвращает статус персональных данных.
     *
     * @return string Статус персональных данных
     */
    public function getStatus();

    /**
     * Устанавливает статус персональных данных.
     *
     * @param string $status Статус персональных данных
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * Возвращает cancellation_details.
     *
     * @return PersonalDataCancellationDetails|null
     */
    public function getCancellationDetails();

    /**
     * Устанавливает cancellation_details.
     *
     * @param PersonalDataCancellationDetails|array|null $cancellation_details
     *
     * @return $this
     */
    public function setCancellationDetails($cancellation_details);

    /**
     * Возвращает created_at.
     *
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * Устанавливает время создания персональных данных.
     *
     * @param DateTime|string|int $created_at Время создания персональных данных
     *
     * @return $this
     */
    public function setCreatedAt($created_at);

    /**
     * Возвращает expires_at.
     *
     * @return DateTime|null
     */
    public function getExpiresAt();

    /**
     * Устанавливает срок жизни объекта персональных данных.
     *
     * @param DateTime|string|int|null $expires_at Срок жизни объекта персональных данных
     *
     * @return $this
     */
    public function setExpiresAt($expires_at = null);

    /**
     * Возвращает metadata.
     *
     * @return array|null
     */
    public function getMetadata();

    /**
     * Устанавливает metadata.
     *
     * @param Metadata|array|null $metadata Любые дополнительные данные, которые нужны вам для работы (например, ваш внутренний идентификатор заказа).
     *
     * @return $this
     */
    public function setMetadata($metadata = null);
}
