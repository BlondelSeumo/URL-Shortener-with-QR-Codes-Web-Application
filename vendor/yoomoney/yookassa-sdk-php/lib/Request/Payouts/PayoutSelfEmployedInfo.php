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

namespace YooKassa\Request\Payouts;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\SelfEmployed\SelfEmployedInterface;

/**
 * Класс, представляющий модель PayoutSelfEmployedInfo.
 *
 * Данные самозанятого, который получит выплату. Необходимо передавать, если вы делаете выплату [самозанятому](/developers/payouts/scenario-extensions/self-employed). Только для обычных выплат.
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 *
 * @property string $id Идентификатор самозанятого в ЮKassa
 */
class PayoutSelfEmployedInfo extends AbstractObject
{
    /**
     * Идентификатор самозанятого в ЮKassa.
     *
     * @var string
     */
    private $_id;

    /**
     * Возвращает идентификатор самозанятого.
     *
     * @return string Идентификатор самозанятого
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
            throw new InvalidPropertyValueTypeException('Invalid PayoutSelfEmployedInfo id value type', 0, 'PayoutSelfEmployedInfo.id', $id);
        }
        $length = mb_strlen($id, 'utf-8');
        if ($length < SelfEmployedInterface::MIN_LENGTH_ID || $length > SelfEmployedInterface::MAX_LENGTH_ID) {
            throw new InvalidPropertyValueException('Invalid PayoutSelfEmployedInfo id value', 0, 'PayoutSelfEmployedInfo.id', $id);
        }
        $this->_id = (string)$id;

        return $this;
    }
}
