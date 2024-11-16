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

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Класс, представляющий модель PersonalDataCancellationDetails.
 *
 * Комментарий к статусу ~`canceled`: кто и по какой причине аннулировал хранение данных.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 */

class PersonalDataCancellationDetails extends AbstractObject
{
    /**
     * Участник процесса, который принял решение о прекращении хранения персональных данных.
     *
     * Возможное значение:
     * * `yoo_money` — ЮKassa.
     *
     * @var string
     */
    private $_party;

    /**
     * Причина прекращения хранения персональных данных.
     *
     * Возможное значение:
     * * `expired_by_timeout` — истек срок хранения или использования персональных данных.
     *
     * @var string
     */
    private $_reason;


    /**
     * Возвращает party.
     *
     * @return string
     */
    public function getParty()
    {
        return $this->_party;
    }

    /**
     * Устанавливает участника процесса, который принял решение о прекращении хранения персональных данных.
     *
     * @param string $value Участник процесса, который принял решение о прекращении хранения персональных данных
     *
     * @return self
     */
    public function setParty($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty party value', 0, 'PersonalDataCancellationDetails.party');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid party value type', 0, 'PersonalDataCancellationDetails.party', $value);
        }
        $this->_party = strtolower((string)$value);

        return $this;
    }

    /**
     * Возвращает reason.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->_reason;
    }

    /**
     * Устанавливает причину прекращения хранения персональных данных.
     *
     * @param string $value Причина прекращения хранения персональных данных
     *
     * @return self
     */
    public function setReason($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty reason value', 0, 'PersonalDataCancellationDetails.reason');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid reason value type', 0, 'PersonalDataCancellationDetails.reason');
        }
        $this->_reason = strtolower((string)$value);

        return $this;
    }
}
