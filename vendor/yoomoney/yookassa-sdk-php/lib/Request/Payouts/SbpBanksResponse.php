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

use Exception;
use YooKassa\Common\AbstractObject;
use YooKassa\Model\Payout\SbpParticipantBank;

/**
 * Класс, представляющий модель GetSbpBanksResponse.
 *
 * Список участников СБП, отсортированный по идентификатору участника в порядке убывания (desc)
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 */

class SbpBanksResponse extends AbstractObject
{
    /**
     * Формат выдачи результатов запроса. Возможное значение: `list` (список).
     *
     * @var string
     */
    private $_type;

    /**
     * @var SbpParticipantBank[]|array
     */
    private $_items;

    /**
     * Конструктор, устанавливает свойства объекта из пришедшего из API ассоциативного массива
     *
     * @param array $sourceArray Массив настроек, пришедший от API
     * @throws Exception
     */
    public function fromArray($sourceArray)
    {
        parent::fromArray($sourceArray);
        $this->_items = array();
        foreach ($sourceArray['items'] as $paymentInfo) {
            $this->_items[] = new SbpParticipantBank($paymentInfo);
        }
    }

    /**
     * Возвращает type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Устанавливает type.
     *
     * @param string $type Формат выдачи результатов запроса. Возможное значение: `list` (список).
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->_type = $type;

        return $this;
    }

    /**
     * Возвращает items.
     *
     * @return SbpParticipantBank[]|array
     */
    public function getItems()
    {
        return $this->_items;
    }
}
