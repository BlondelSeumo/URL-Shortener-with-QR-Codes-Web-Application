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

namespace YooKassa\Model\Receipt;

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Class MarkCodeInfo
 *
 * Код товара (тег в 54 ФЗ — 1163).
 * Обязателен при использовании протокола ФФД 1.2, если товар нужно маркировать. Должно быть заполнено хотя бы одно из полей.
 *
 * @package YooKassa
 *
 * @property string $markCodeRaw Код товара в том виде, в котором он был прочитан сканером (тег в 54 ФЗ — 2000)
 * @property string $mark_code_raw Код товара в том виде, в котором он был прочитан сканером (тег в 54 ФЗ — 2000)
 * @property string $unknown Нераспознанный код товара (тег в 54 ФЗ — 1300)
 * @property string $ean_8 Код товара в формате EAN-8 (тег в 54 ФЗ — 1301)
 * @property string $ean_13 Код товара в формате EAN-13 (тег в 54 ФЗ — 1302)
 * @property string $itf_14 Код товара в формате ITF-14 (тег в 54 ФЗ — 1303)
 * @property string $gs_10 Код товара в формате GS1.0 (тег в 54 ФЗ — 1304)
 * @property string $gs_1m Код товара в формате GS1.M (тег в 54 ФЗ — 1305)
 * @property string $short Код товара в формате короткого кода маркировки (тег в 54 ФЗ — 1306)
 * @property string $fur Контрольно-идентификационный знак мехового изделия (тег в 54 ФЗ — 1307)
 * @property string $egais_20 Код товара в формате ЕГАИС-2.0 (тег в 54 ФЗ — 1308)
 * @property string $egais_30 Код товара в формате ЕГАИС-3.0 (тег в 54 ФЗ — 1309)
 */
class MarkCodeInfo extends AbstractObject
{
    /** @var int Минимальная длинна поля */
    const MIN_LENGTH = 1;
    /** @var int Максимальная длина UNKNOWN */
    const MAX_UNKNOWN_LENGTH = 32;
    /** @var int Максимальная длина EAN_8 */
    const MAX_EAN_8_LENGTH = 8;
    /** @var int Максимальная длина EAN_13 */
    const MAX_EAN_13_LENGTH = 13;
    /** @var int Максимальная длина ITF_14 */
    const MAX_ITF_14_LENGTH = 14;
    /** @var int Максимальная длина GS_10 */
    const MAX_GS_10_LENGTH = 38;
    /** @var int Максимальная длина GS_1M */
    const MAX_GS_1M_LENGTH = 200;
    /** @var int Максимальная длина HORT */
    const MAX_SHORT_LENGTH = 38;
    /** @var int Максимальная длина FUR */
    const MAX_FUR_LENGTH = 20;
    /** @var int Максимальная длина EGAIS_20 */
    const MAX_EGAIS_20_LENGTH = 23;
    /** @var int Максимальная длина EGAIS_30 */
    const MAX_EGAIS_30_LENGTH = 14;

    /** @var string Код товара в том виде, в котором он был прочитан сканером (тег в 54 ФЗ — 2000) */
    private $_mark_code_raw;

    /** @var string Нераспознанный код товара (тег в 54 ФЗ — 1300) */
    private $_unknown;

    /** @var string Код товара в формате EAN-8 (тег в 54 ФЗ — 1301) */
    private $_ean_8;

    /** @var string Код товара в формате EAN-13 (тег в 54 ФЗ — 1302) */
    private $_ean_13;

    /** @var string Код товара в формате ITF-14 (тег в 54 ФЗ — 1303) */
    private $_itf_14;

    /** @var string Код товара в формате GS1.0 (тег в 54 ФЗ — 1304) */
    private $_gs_10;

    /** @var string Код товара в формате GS1.M (тег в 54 ФЗ — 1305) */
    private $_gs_1m;

    /** @var string Код товара в формате короткого кода маркировки (тег в 54 ФЗ — 1306) */
    private $_short;

    /** @var string Контрольно-идентификационный знак мехового изделия (тег в 54 ФЗ — 1307) */
    private $_fur;

    /** @var string Код товара в формате ЕГАИС-2.0 (тег в 54 ФЗ — 1308) */
    private $_egais_20;

    /** @var string Код товара в формате ЕГАИС-3.0 (тег в 54 ФЗ — 1309) */
    private $_egais_30;

    /**
     * Возвращает исходный код товара
     * @return string Исходный код товара
     */
    public function getMarkCodeRaw()
    {
        return $this->_mark_code_raw;
    }

    /**
     * Устанавливает исходный код товара
     * @param string $value Исходный код товара
     * @return MarkCodeInfo
     */
    public function setMarkCodeRaw($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty mark_code_raw value', 0, 'MarkCodeInfo.mark_code_raw');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid mark_code_raw value type', 0, 'MarkCodeInfo.mark_code_raw', $value);
        }

        $this->_mark_code_raw = (string)$value;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnknown()
    {
        return $this->_unknown;
    }

    /**
     * @param $value
     * @return MarkCodeInfo
     */
    public function setUnknown($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty unknown value', 0, 'MarkCodeInfo.unknown');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid unknown value type', 0, 'MarkCodeInfo.unknown', $value);
        }
        if (mb_strlen((string)$value) > self::MAX_UNKNOWN_LENGTH) {
            throw new InvalidPropertyValueException('Invalid unknown value length: "' . $value . '"', 0, 'MarkCodeInfo.unknown', $value);
        }

        $this->_unknown = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getEan8()
    {
        return $this->_ean_8;
    }

    /**
     * @param string $value
     * @return MarkCodeInfo
     */
    public function setEan8($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty ean_8 value', 0, 'MarkCodeInfo.ean_8');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid ean_8 value type', 0, 'MarkCodeInfo.ean_8', $value);
        }
        if (mb_strlen((string)$value) !== self::MAX_EAN_8_LENGTH) {
            throw new InvalidPropertyValueException('Invalid ean_8 value length: "' . $value . '"', 0, 'MarkCodeInfo.ean_8', $value);
        }

        $this->_ean_8 = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getEan13()
    {
        return $this->_ean_13;
    }

    /**
     * @param string $value
     * @return MarkCodeInfo
     */
    public function setEan13($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty ean_8 value', 0, 'MarkCodeInfo.ean_8');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid ean_8 value type', 0, 'MarkCodeInfo.ean_8', $value);
        }
        if (mb_strlen((string)$value) !== self::MAX_EAN_13_LENGTH) {
            throw new InvalidPropertyValueException('Invalid ean_8 value length: "' . $value . '"', 0, 'MarkCodeInfo.ean_8', $value);
        }

        $this->_ean_13 = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getItf14()
    {
        return $this->_itf_14;
    }

    /**
     * @param string $value
     * @return MarkCodeInfo
     */
    public function setItf14($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty itf_14 value', 0, 'MarkCodeInfo.itf_14');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid itf_14 value type', 0, 'MarkCodeInfo.itf_14', $value);
        }
        if (mb_strlen((string)$value) !== self::MAX_ITF_14_LENGTH) {
            throw new InvalidPropertyValueException('Invalid itf_14 value length: "' . $value . '"', 0, 'MarkCodeInfo.itf_14', $value);
        }

        $this->_itf_14 = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getGs10()
    {
        return $this->_gs_10;
    }

    /**
     * @param string $value
     * @return MarkCodeInfo
     */
    public function setGs10($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty gs_10 value', 0, 'MarkCodeInfo.gs_10');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid gs_10 value type', 0, 'MarkCodeInfo.gs_10', $value);
        }
        if (mb_strlen((string)$value) > self::MAX_GS_10_LENGTH) {
            throw new InvalidPropertyValueException('Invalid gs_10 value length: "' . $value . '"', 0, 'MarkCodeInfo.gs_10', $value);
        }

        $this->_gs_10 = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getGs1m()
    {
        return $this->_gs_1m;
    }

    /**
     * @param $value
     * @return MarkCodeInfo
     */
    public function setGs1m($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty gs_1m value', 0, 'MarkCodeInfo.gs_1m');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid gs_1m value type', 0, 'MarkCodeInfo.gs_1m', $value);
        }
        if (mb_strlen((string)$value) > self::MAX_GS_1M_LENGTH) {
            throw new InvalidPropertyValueException('Invalid gs_1m value length: "' . $value . '"', 0, 'MarkCodeInfo.gs_1m', $value);
        }

        $this->_gs_1m = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getShort()
    {
        return $this->_short;
    }

    /**
     * @param $value
     * @return MarkCodeInfo
     */
    public function setShort($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty short value', 0, 'MarkCodeInfo.short');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid short value type', 0, 'MarkCodeInfo.short', $value);
        }
        if (mb_strlen((string)$value) > self::MAX_SHORT_LENGTH) {
            throw new InvalidPropertyValueException('Invalid short value length: "' . $value . '"', 0, 'MarkCodeInfo.short', $value);
        }

        $this->_short = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getFur()
    {
        return $this->_fur;
    }

    /**
     * @param $value
     * @return MarkCodeInfo
     */
    public function setFur($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty fur value', 0, 'MarkCodeInfo.fur');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid fur value type', 0, 'MarkCodeInfo.fur', $value);
        }
        if (mb_strlen((string)$value) !== self::MAX_FUR_LENGTH) {
            throw new InvalidPropertyValueException('Invalid fur value length: "' . $value . '"', 0, 'MarkCodeInfo.fur', $value);
        }

        $this->_fur = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getEgais20()
    {
        return $this->_egais_20;
    }

    /**
     * @param $value
     * @return MarkCodeInfo
     */
    public function setEgais20($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty egais_20 value', 0, 'MarkCodeInfo.egais_20');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid egais_20 value type', 0, 'MarkCodeInfo.egais_20', $value);
        }
        if (mb_strlen((string)$value) !== self::MAX_EGAIS_20_LENGTH) {
            throw new InvalidPropertyValueException('Invalid egais_20 value length: "' . $value . '"', 0, 'MarkCodeInfo.egais_20', $value);
        }

        $this->_egais_20 = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getEgais30()
    {
        return $this->_egais_30;
    }

    /**
     * @param $value
     * @return MarkCodeInfo
     */
    public function setEgais30($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty egais_30 value', 0, 'MarkCodeInfo.egais_30');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid egais_30 value type', 0, 'MarkCodeInfo.egais_30', $value);
        }
        if (mb_strlen((string)$value) !== self::MAX_EGAIS_30_LENGTH) {
            throw new InvalidPropertyValueException('Invalid egais_30 value length: "' . $value . '"', 0, 'MarkCodeInfo.egais_30', $value);
        }

        $this->_egais_30 = $value;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $fields = array(
            'mark_code_raw', 'unknown', 'ean_8', 'ean_13', 'itf_14', 'gs_10', 'gs_1m', 'short', 'fur', 'egais_20', 'egais_30'
        );
        $result = array();
        foreach ($fields as $key) {
            $value = $this->{$key};
            if (!empty($value)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
