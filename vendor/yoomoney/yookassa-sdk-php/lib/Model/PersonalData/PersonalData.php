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
use Exception;
use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\Metadata;

/**
 * Класс, представляющий модель PersonalData.
 *
 * Информация о персональных данных
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 *
 * @property string $id Идентификатор персональных данных
 * @property string $type Тип персональных данных
 * @property string $status Текущий статус персональных данных
 * @property DateTime $createdAt Время создания персональных данных
 * @property DateTime $created_at Время создания персональных данных
 * @property DateTime|null $expiresAt Срок жизни объекта персональных данных
 * @property DateTime|null $expires_at Срок жизни объекта персональных данных
 * @property PersonalDataCancellationDetails $cancellationDetails Комментарий к отмене выплаты
 * @property PersonalDataCancellationDetails $cancellation_details Комментарий к отмене выплаты
 * @property Metadata $metadata Метаданные выплаты указанные мерчантом
 */
class PersonalData extends AbstractObject implements PersonalDataInterface
{
    /**
     * Идентификатор персональных данных, сохраненных в ЮKassa.
     *
     * @var string
     */
    private $_id;

    /**
     * Тип персональных данных — цель, для которой вы будете использовать данные.
     *
     * Возможное значение:
     * * `sbp_payout_recipient` — выплаты с [проверкой получателя](/developers/payouts/scenario-extensions/recipient-check).
     *
     * @var string
     */
    private $_type;

    /**
     * Статус персональных данных.
     *
     * Возможные значения:
     *  * `waiting_for_operation` — данные сохранены, но не использованы при проведении выплаты;
     *  * `active` — данные сохранены и использованы при проведении выплаты; данные можно использовать повторно до срока, указанного в параметре `expires_at`;
     *  * `canceled` — хранение данных отменено, данные удалены, инициатор и причина отмены указаны в объекте `cancellation_details` (финальный и неизменяемый статус).
     *
     * [Подробнее о жизненном цикле персональных данных](/developers/payouts/scenario-extensions/recipient-check#lifecircle)
     *
     * @var string
     */
    private $_status;

    /**
     * Комментарий к статусу canceled: кто и по какой причине аннулировал хранение данных.
     *
     * @var PersonalDataCancellationDetails|null
     */
    private $_cancellation_details;

    /**
     * Время создания персональных данных.
     *
     * Указывается по [UTC](https://ru.wikipedia.org/wiki/Всемирное_координированное_время) и передается в формате [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601). Пример: ~`2017-11-03T11:52:31.827Z`
     *
     * @var DateTime
     */
    private $_created_at;

    /**
     * Срок жизни объекта персональных данных — время, до которого вы можете использовать персональные данные при проведении операций.
     *
     * Указывается только для объекта в статусе ~`active`. Указывается по [UTC](https://ru.wikipedia.org/wiki/Всемирное_координированное_время) и передается в формате [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601). Пример: ~`2017-11-03T11:52:31.827Z`
     *
     * @var DateTime|null
     */
    private $_expires_at;

    /**
     * Любые дополнительные данные, которые нужны вам для работы (например, ваш внутренний идентификатор заказа).
     *
     * Передаются в виде набора пар «ключ-значение» и возвращаются в ответе от ЮKassa.
     * Ограничения: максимум 16 ключей, имя ключа не больше 32 символов, значение ключа не больше 512 символов, тип данных — строка в формате UTF-8.
     *
     * @var array|null
     */
    private $_metadata;


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
     * Устанавливает id.
     *
     * @param string $id Идентификатор персональных данных, сохраненных в ЮKassa.
     *
     * @return $this
     */
    public function setId($id)
    {
        if (!TypeCast::canCastToString($id)) {
            throw new InvalidPropertyValueTypeException('Invalid PersonalData id value type', 0, 'PersonalData.id', $id);
        }
        $length = mb_strlen($id, 'utf-8');
        if ($length < PersonalDataInterface::MIN_LENGTH_ID || $length > PersonalDataInterface::MAX_LENGTH_ID) {
            throw new InvalidPropertyValueException('Invalid PersonalData id value', 0, 'PersonalData.id', $id);
        }
        $this->_id = (string)$id;

        return $this;
    }

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
            throw new InvalidPropertyValueTypeException('Invalid PersonalData type value type', 0, 'PersonalData.type', $type);
        }
        if (!PersonalDataType::valueExists((string)$type)) {
            throw new InvalidPropertyValueException('Invalid PersonalData type value', 0, 'PersonalData.type', $type);
        }
        $this->_type = (string)$type;

        return $this;
    }

    /**
     * Возвращает статус персональных данных.
     *
     * @return string Статус персональных данных
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Устанавливает статус персональных данных.
     *
     * @param string $status Статус персональных данных
     *
     * @return $this
     */
    public function setStatus($status)
    {
        if (!TypeCast::canCastToEnumString($status)) {
            throw new InvalidPropertyValueTypeException('Invalid PersonalData status value type', 0, 'PersonalData.status', $status);
        }
        if (!PersonalDataStatus::valueExists((string)$status)) {
            throw new InvalidPropertyValueException('Invalid PersonalData status value', 0, 'PersonalData.status', $status);
        }
        $this->_status = (string)$status;

        return $this;
    }

    /**
     * Возвращает cancellation_details.
     *
     * @return PersonalDataCancellationDetails|null
     */
    public function getCancellationDetails()
    {
        return $this->_cancellation_details;
    }

    /**
     * Устанавливает cancellation_details.
     *
     * @param PersonalDataCancellationDetails|array|null $cancellation_details
     *
     * @return $this
     */
    public function setCancellationDetails($cancellation_details)
    {
        if ($cancellation_details === null) {
            $this->_cancellation_details = null;
        } elseif (is_array($cancellation_details)) {
            $this->_cancellation_details = new PersonalDataCancellationDetails($cancellation_details);
        } elseif ($cancellation_details instanceof PersonalDataCancellationDetails) {
            $this->_cancellation_details = $cancellation_details;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "cancellation_details" parameter in PersonalData',
                0,
                'PersonalData.cancellation_details',
                $cancellation_details
            );
        }

        return $this;
    }

    /**
     * Возвращает created_at.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->_created_at;
    }

    /**
     * Устанавливает время создания персональных данных.
     *
     * @param DateTime|string|int $created_at Время создания персональных данных
     *
     * @return $this
     * @throws Exception
     */
    public function setCreatedAt($created_at)
    {
        if ($created_at === null || $created_at === '') {
            throw new EmptyPropertyValueException('Empty created_at value', 0, 'PersonalData.createdAt');
        }
        if (!TypeCast::canCastToDateTime($created_at)) {
            throw new InvalidPropertyValueTypeException('Invalid created_at value', 0, 'PersonalData.createdAt', $created_at);
        }
        $dateTime = TypeCast::castToDateTime($created_at);
        if ($dateTime === null) {
            throw new InvalidPropertyValueException('Invalid created_at value', 0, 'PersonalData.createdAt', $created_at);
        }
        $this->_created_at = $dateTime;

        return $this;
    }

    /**
     * Возвращает срок жизни объекта персональных данных.
     *
     * @return DateTime|null Срок жизни объекта персональных данных
     */
    public function getExpiresAt()
    {
        return $this->_expires_at;
    }

    /**
     * Устанавливает срок жизни объекта персональных данных.
     *
     * @param DateTime|string|int|null $expires_at Срок жизни объекта персональных данных
     *
     * @return $this
     * @throws Exception
     */
    public function setExpiresAt($expires_at = null)
    {
        if ($expires_at === null || $expires_at === '') {
            $this->_expires_at = null;
        } elseif (TypeCast::canCastToDateTime($expires_at)) {
            $dateTime = TypeCast::castToDateTime($expires_at);
            if ($dateTime === null) {
                throw new InvalidPropertyValueException('Invalid expires_at value', 0, 'PersonalData.expiresAt', $expires_at);
            }
            $this->_expires_at = $dateTime;
        } else {
            throw new InvalidPropertyValueTypeException('Invalid expires_at value', 0, 'PersonalData.expiresAt', $expires_at);
        }

        return $this;
    }

    /**
     * Возвращает любые дополнительные данные.
     *
     * @return array|null Любые дополнительные данные
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }

    /**
     * Устанавливает любые дополнительные данные.
     *
     * @param Metadata|array|null $metadata Любые дополнительные данные
     *
     * @return $this
     */
    public function setMetadata($metadata = null)
    {
        if ($metadata === null || $metadata === '') {
            $this->_metadata = null;
        } elseif (is_array($metadata)) {
            $this->_metadata = new Metadata($metadata);
        } elseif ($metadata instanceof Metadata) {
            $this->_metadata = $metadata;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid metadata type for "metadata" parameter in PersonalData',
                0,
                'PersonalData.metadata',
                $metadata
            );
        }

        return $this;
    }
}
