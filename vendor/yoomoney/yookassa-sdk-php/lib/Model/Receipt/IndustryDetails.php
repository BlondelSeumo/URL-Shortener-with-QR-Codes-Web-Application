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

use DateTime;
use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\TypeCast;

/**
 * Class IndustryDetails
 *
 * Данные отраслевого реквизита
 *
 * @package YooKassa
 *
 * @property string $federalId Идентификатор федерального органа исполнительной власти (тег в 54 ФЗ — 1262)
 * @property string $federal_id Идентификатор федерального органа исполнительной власти (тег в 54 ФЗ — 1262)
 * @property DateTime $documentDate Дата документа основания (тег в 54 ФЗ — 1263)
 * @property DateTime $document_date Дата документа основания (тег в 54 ФЗ — 1263)
 * @property string $documentNumber Номер нормативного акта федерального органа исполнительной власти (тег в 54 ФЗ — 1264)
 * @property string $document_number Номер нормативного акта федерального органа исполнительной власти (тег в 54 ФЗ — 1264)
 * @property string $value Значение отраслевого реквизита (тег в 54 ФЗ — 1265)
 */
class IndustryDetails extends AbstractObject
{
    /** @var int Максимальная длинна номера документа */
    const DOCUMENT_NUMBER_MAX_LENGTH = 32;
    /** @var int Максимальная длинна значение отраслевого реквизита */
    const VALUE_MAX_LENGTH = 256;
    /** @var string Формат даты документа */
    const DOCUMENT_DATE_FORMAT = 'Y-m-d';

    /**
     * @var string Идентификатор федерального органа исполнительной власти (тег в 54 ФЗ — 1262)
     */
    private $_federalId;

    /**
     * @var DateTime Дата документа основания (тег в 54 ФЗ — 1263). Передается в формате [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601)
     */
    private $_documentDate;

    /**
     * @var string Номер нормативного акта федерального органа исполнительной власти, регламентирующего порядок заполнения реквизита «значение отраслевого реквизита» (тег в 54 ФЗ — 1264)
     */
    private $_documentNumber;

    /**
     * @var string Значение отраслевого реквизита (тег в 54 ФЗ — 1265)
     */
    private $_value;

    /**
     * Возвращает идентификатор федерального органа исполнительной власти
     * @return string Идентификатор федерального органа исполнительной власти
     */
    public function getFederalId()
    {
        return $this->_federalId;
    }

    /**
     * Устанавливает идентификатор федерального органа исполнительной власти
     * @param string $value Идентификатор федерального органа исполнительной власти
     */
    public function setFederalId($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty federal_id value', 0, 'IndustryDetails.federalId');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid federal_id value type', 0, 'IndustryDetails.federalId', $value);
        }

        $this->_federalId = (string)$value;

        return $this;
    }

    /**
     * Возвращает дату документа основания
     * @return DateTime Дата документа основания
     */
    public function getDocumentDate()
    {
        return $this->_documentDate;
    }

    /**
     * Устанавливает дату документа основания
     * @param string|DateTime $value Дата документа основания
     * @throws \Exception
     */
    public function setDocumentDate($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty document_date value', 0, 'IndustryDetails.documentDate');
        }
        if (!TypeCast::canCastToDateTime($value)) {
            throw new InvalidPropertyValueTypeException('Invalid document_date value', 0, 'IndustryDetails.documentDate', $value);
        }
        $dateTime = TypeCast::castToDateTime($value);
        if ($dateTime === null) {
            throw new InvalidPropertyValueException('Invalid document_date value', 0, 'IndustryDetails.documentDate', $value);
        }
        $this->_documentDate = $dateTime;

        return $this;
    }

    /**
     * Возвращает номер нормативного акта федерального органа исполнительной власти
     * @return string Номер нормативного акта федерального органа исполнительной власти
     */
    public function getDocumentNumber()
    {
        return $this->_documentNumber;
    }

    /**
     * Устанавливает номер нормативного акта федерального органа исполнительной власти
     * @param string $value Номер нормативного акта федерального органа исполнительной власти
     */
    public function setDocumentNumber($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty document_number value', 0, 'IndustryDetails.documentNumber');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid document_number value type', 0, 'IndustryDetails.documentNumber', $value);
        }
        if (mb_strlen((string)$value) > self::DOCUMENT_NUMBER_MAX_LENGTH) {
            throw new InvalidPropertyValueException('Invalid document_number value length: "' . $value . '"', 0, 'IndustryDetails.documentNumber', $value);
        }

        $this->_documentNumber = (string)$value;

        return $this;
    }

    /**
     * Возвращает значение отраслевого реквизита
     * @return string Значение отраслевого реквизита
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Устанавливает значение отраслевого реквизита
     * @param string $value Значение отраслевого реквизита
     */
    public function setValue($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty value', 0, 'IndustryDetails.value');
        }
        if (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException('Invalid value type', 0, 'IndustryDetails.value', $value);
        }
        if (mb_strlen((string)$value) > self::VALUE_MAX_LENGTH) {
            throw new InvalidPropertyValueException('Invalid value length: "' . $value . '"', 0, 'IndustryDetails.value', $value);
        }

        $this->_value = (string)$value;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $result = parent::jsonSerialize();
        $result['document_date'] = $this->getDocumentDate()->format(self::DOCUMENT_DATE_FORMAT);

        return $result;
    }
}
