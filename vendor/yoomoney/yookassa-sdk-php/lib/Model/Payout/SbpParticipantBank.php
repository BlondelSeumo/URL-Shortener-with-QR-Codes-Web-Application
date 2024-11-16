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

namespace YooKassa\Model\Payout;

use YooKassa\Common\AbstractObject;

/**
 * Класс, представляющий модель SbpParticipantBank.
 *
 * Участник СБП (Системы быстрых платежей ЦБ РФ)
 *
 * @package YooKassa\Model
 * @author  cms@yoomoney.ru
 */

class SbpParticipantBank extends AbstractObject
{
    /**
     * Идентификатор банка или платежного сервиса в СБП.
     *
     * @var string
     */
    private $_bank_id;

    /**
     * Название банка или платежного сервиса в СБП.
     *
     * @var string
     */
    private $_name;

    /**
     * Банковский идентификационный код (БИК) банка или платежного сервиса.
     *
     * @var string
     */
    private $_bic;


    /**
     * Возвращает bank_id.
     *
     * @return string
     */
    public function getBankId()
    {
        return $this->_bank_id;
    }

    /**
     * Устанавливает bank_id.
     *
     * @param string $bank_id Идентификатор банка или платежного сервиса в СБП.
     *
     * @return $this
     */
    public function setBankId($bank_id)
    {
        $this->_bank_id = $bank_id;

        return $this;
    }

    /**
     * Возвращает name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Устанавливает name.
     *
     * @param string $name Название банка или платежного сервиса в СБП.
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    /**
     * Возвращает bic.
     *
     * @return string
     */
    public function getBic()
    {
        return $this->_bic;
    }

    /**
     * Устанавливает bic.
     *
     * @param string $bic Банковский идентификационный код (БИК) банка или платежного сервиса.
     *
     * @return $this
     */
    public function setBic($bic)
    {
        $this->_bic = $bic;

        return $this;
    }
}
