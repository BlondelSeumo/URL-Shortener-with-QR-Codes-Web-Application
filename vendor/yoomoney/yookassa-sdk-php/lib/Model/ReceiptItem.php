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

use YooKassa\Common\AbstractObject;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\ProductCode;
use YooKassa\Helpers\TypeCast;
use YooKassa\Model\Receipt\AgentType;
use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\MarkCodeInfo;
use YooKassa\Model\Receipt\MarkQuantity;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Model\Receipt\ReceiptItemMeasure;

/**
 * Информация о товарной позиции в заказе, позиция фискального чека
 *
 * @property string $description Наименование товара (тег в 54 ФЗ — 1030)
 * @property float $quantity Количество (тег в 54 ФЗ — 1023)
 * @property-read float $amount Суммарная стоимость покупаемого товара в копейках/центах
 * @property AmountInterface $price Цена товара (тег в 54 ФЗ — 1079)
 * @property Supplier $supplier Информация о поставщике товара или услуги (тег в 54 ФЗ — 1224)
 * @property string $agentType Тип посредника, реализующего товар или услугу
 * @property string $agent_type Тип посредника, реализующего товар или услугу
 * @property int $vatCode Ставка НДС (тег в 54 ФЗ — 1199), число 1-6
 * @property int $vat_code Ставка НДС (тег в 54 ФЗ — 1199), число 1-6
 * @property string $paymentSubject Признак предмета расчета (тег в 54 ФЗ — 1212)
 * @property string $payment_subject Признак предмета расчета (тег в 54 ФЗ — 1212)
 * @property string $paymentMode Признак способа расчета (тег в 54 ФЗ — 1214)
 * @property string $payment_mode Признак способа расчета (тег в 54 ФЗ — 1214)
 * @property string $productCode Код товара (тег в 54 ФЗ — 1162)
 * @property string $product_code Код товара (тег в 54 ФЗ — 1162)
 * @property MarkCodeInfo $markCodeInfo Код товара (тег в 54 ФЗ — 1163)
 * @property MarkCodeInfo $mark_code_info Код товара (тег в 54 ФЗ — 1163)
 * @property string $measure Мера количества предмета расчета (тег в 54 ФЗ — 2108)
 * @property string $markMode Режим обработки кода маркировки (тег в 54 ФЗ — 2102)
 * @property string $mark_mode Режим обработки кода маркировки (тег в 54 ФЗ — 2102)
 * @property MarkQuantity $markQuantity Дробное количество маркированного товара (тег в 54 ФЗ — 1291)
 * @property MarkQuantity $mark_quantity Дробное количество маркированного товара (тег в 54 ФЗ — 1291)
 * @property IndustryDetails[] $paymentSubjectIndustryDetails Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260)
 * @property IndustryDetails[] $payment_subject_industry_details Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260)
 * @property string $countryOfOriginCode Код страны происхождения товара (тег в 54 ФЗ — 1230)
 * @property string $country_of_origin_code Код страны происхождения товара (тег в 54 ФЗ — 1230)
 * @property string $customsDeclarationNumber Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231
 * @property string $customs_declaration_number Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231
 * @property string $additionalPaymentSubjectProps Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191)
 * @property string $additional_payment_subject_props Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191)
 * @property float $excise Сумма акциза товара с учетом копеек (тег в 54 ФЗ — 1229)
 * @property-write bool $isShipping Флаг доставки
 */
class ReceiptItem extends AbstractObject implements ReceiptItemInterface
{
    /** @var int Максимальная длинна наименования товара */
    const DESCRIPTION_MAX_LENGTH = 128;
    /** @var int Максимальная длинна дополнительного реквизита предмета расчета */
    const ADD_PROPS_MAX_LENGTH = 64;

    /**
     * @var string Наименование товара (тег в 54 ФЗ — 1030)
     */
    private $_description;

    /**
     * @var float Количество (тег в 54 ФЗ — 1023)
     */
    private $_quantity;

    /**
     * @var string Мера количества предмета расчета (тег в 54 ФЗ — 2108) — единица измерения товара, например штуки, граммы.
     * Обязателен при использовании ФФД 1.2.
     */
    private $_measure;

    /**
     * @var MarkQuantity Дробное количество маркированного товара (тег в 54 ФЗ — 1291).
     */
    private $_markQuantity;

    /**
     * @var ReceiptItemAmount Цена товара (тег в 54 ФЗ — 1079)
     */
    private $_amount;

    /**
     * @var int Ставка НДС, число 1-6 (тег в 54 ФЗ — 1199)
     */
    private $_vatCode;

    /**
     * @var string Признак предмета расчета (тег в 54 ФЗ — 1212)
     */
    private $_paymentSubject;

    /**
     * @var string Признак способа расчета (тег в 54 ФЗ — 1214)
     */
    private $_paymentMode;

    /**
     * @var string Код страны происхождения товара (тег в 54 ФЗ — 1230)
     */
    private $_countryOfOriginCode;

    /**
     * @var string Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231
     */
    private $_customsDeclarationNumber;

    /**
     * @var float Сумма акциза товара с учетом копеек (тег в 54 ФЗ — 1229). Десятичное число с точностью до 2 символов после точки.
     */
    private $_excise;

    /**
     * @var string Код товара (тег в 54 ФЗ — 1162)
     */
    private $_productCode;

    /**
     * @var MarkCodeInfo Код товара (тег в 54 ФЗ — 1163).
     * Обязателен при использовании протокола ФФД 1.2, если товар нужно маркировать. Должно быть заполнено хотя бы одно из полей.
     */
    private $_markCodeInfo;

    /**
     * @var string Режим обработки кода маркировки (тег в 54 ФЗ — 2102). Должен принимать значение равное «0».
     */
    private $_markMode;

    /**
     * @var IndustryDetails[] Отраслевой реквизит чека (тег в 54 ФЗ — 1260).
     */
    private $_paymentSubjectIndustryDetails;

    /**
     * @var string Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191). Не более 64 символов.
     * Можно передавать, если вы отправляете данные для формирования чека по сценарию Сначала платеж, потом чек
     */
    private $_additionalPaymentSubjectProps;

    /**
     * @var Supplier Информация о поставщике товара или услуги (тег в 54 ФЗ — 1224)
     */
    private $_supplier;

    /**
     * @var string Тип посредника, реализующего товар или услугу
     */
    private $_agentType;

    /**
     * @var bool True если текущий айтем доставка, false если нет
     */
    private $_shipping = false;

    /**
     * Возвращает наименование товара
     * @return string Наименование товара
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Устанавливает наименование товара
     *
     * @param string $value Наименование товара
     *
     * @throws EmptyPropertyValueException Выбрасывается если было передано пустое значение
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента была передана не строка
     */
    public function setDescription($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException(
                'Empty description value in ReceiptItem',
                0,
                'ReceiptItem.description'
            );
        } elseif (TypeCast::canCastToString($value)) {
            $castedValue = (string)$value;
            if ($castedValue === '') {
                throw new EmptyPropertyValueException(
                    'Empty description value in ReceiptItem',
                    0,
                    'ReceiptItem.description'
                );
            }
            $this->_description = mb_substr($castedValue, 0, self::DESCRIPTION_MAX_LENGTH);
        } else {
            throw new InvalidPropertyValueTypeException(
                'Empty description value in ReceiptItem',
                0,
                'ReceiptItem.description',
                $value
            );
        }
    }

    /**
     * Возвращает количество товара
     * @return float Количество купленного товара
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * Устанавливает количество покупаемого товара
     *
     * @param int $value Количество
     *
     * @throws EmptyPropertyValueException Выбрасывается если было передано пустое значение
     * @throws InvalidPropertyValueException Выбрасывается если в качестве аргумента был передан ноль
     * или отрицательное число
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента было передано не число
     */
    public function setQuantity($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty quantity value in ReceiptItem', 0, 'ReceiptItem.quantity');
        } elseif (!is_numeric($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid quantity value type in ReceiptItem',
                0,
                'ReceiptItem.quantity',
                $value
            );
        } elseif ($value <= 0.0) {
            throw new InvalidPropertyValueException(
                'Invalid quantity value in ReceiptItem',
                0,
                'ReceiptItem.quantity',
                $value
            );
        } else {
            $this->_quantity = (float)$value;
        }
    }

    /**
     * Возвращает общую стоимость покупаемого товара в копейках/центах
     * @return int Сумма стоимости покупаемого товара
     */
    public function getAmount()
    {
        return (int)round($this->_amount->getIntegerValue() * $this->_quantity);
    }

    /**
     * Возвращает цену товара
     * @return AmountInterface Цена товара
     */
    public function getPrice()
    {
        return $this->_amount;
    }

    /**
     * Устанавливает цену товара
     *
     * @param AmountInterface|array $value Цена товара
     */
    public function setPrice($value)
    {
        if (is_array($value)) {
            $this->_amount = new ReceiptItemAmount($value);
        } elseif ($value instanceof AmountInterface) {
            $this->_amount = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid amount value type in ReceiptItem',
                0,
                'ReceiptItem.amount',
                $value
            );
        }
    }

    /**
     * Возвращает ставку НДС
     * @return int|null Ставка НДС, число 1-6, или null, если ставка не задана
     */
    public function getVatCode()
    {
        return $this->_vatCode;
    }

    /**
     * Устанавливает ставку НДС
     *
     * @param int $value Ставка НДС, число 1-6
     *
     * @throws InvalidPropertyValueException Выбрасывается если в качестве аргумента было передано число меньше одного
     * или больше шести
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента было передано не число
     */
    public function setVatCode($value)
    {
        if ($value === null || $value === '') {
            $this->_vatCode = null;
        } elseif (!is_numeric($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid vatId value type in ReceiptItem',
                0,
                'ReceiptItem.vatId',
                $value
            );
        } elseif ($value < 1 || $value > 6) {
            throw new InvalidPropertyValueException(
                'Invalid vatId value in ReceiptItem',
                0,
                'ReceiptItem.vatId',
                $value
            );
        } else {
            $this->_vatCode = (int)$value;
        }
    }

    /**
     * Возвращает признак предмета расчета
     * @return string|null Признак предмета расчета
     */
    public function getPaymentSubject()
    {
        return $this->_paymentSubject;
    }

    /**
     * Устанавливает признак предмета расчета
     *
     * @param string $value Признак предмета расчета
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента была передана не строка
     */
    public function setPaymentSubject($value)
    {
        if ($value === null || $value === '') {
            $this->_paymentSubject = null;
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid paymentSubject value type in ReceiptItem',
                0,
                'ReceiptItem.paymentSubject'
            );
        } else {
            $this->_paymentSubject = $value;
        }
    }

    /**
     * Возвращает признак способа расчета
     * @return string|null Признак способа расчета
     */
    public function getPaymentMode()
    {
        return $this->_paymentMode;
    }

    /**
     * Устанавливает признак способа расчета
     *
     * @param string $value Признак способа расчета
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента была передана не строка
     */
    public function setPaymentMode($value)
    {
        if ($value === null || $value === '') {
            $this->_paymentMode = null;
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid paymentMode value type',
                0,
                'ReceiptItem.paymentMode',
                $value
            );
        } else {
            $this->_paymentMode = $value;
        }
    }

    /**
     * Возвращает код товара — уникальный номер, который присваивается экземпляру товара при маркировке
     * @return string|null Код товара
     */
    public function getProductCode()
    {
        return $this->_productCode;
    }

    /**
     * Устанавливает код товара — уникальный номер, который присваивается экземпляру товара при маркировке
     *
     * @param string|ProductCode $value Код товара
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента была передана не строка
     */
    public function setProductCode($value)
    {
        if ($value instanceof ProductCode) {
            $value = (string)$value;
        }
        if ($value === null || $value === '') {
            $this->_productCode = null;
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid productCode value type',
                0,
                'ReceiptItem.productCode',
                $value
            );
        } elseif (mb_strlen((string)$value) > 96) {
            throw new InvalidPropertyValueException(
                'Invalid productCode value: "' . $value . '"',
                0,
                'ReceiptItem.productCode',
                $value
            );
        } elseif (!preg_match('/^[0-9A-F ]{2,96}$/', (string)$value)) {
            throw new InvalidPropertyValueException(
                'Invalid productCode value: "' . $value . '"',
                0,
                'ReceiptItem.productCode',
                $value
            );
        } else {
            $this->_productCode = $value;
        }
    }

    /**
     * Возвращает код товара
     * @return MarkCodeInfo Код товара
     */
    public function getMarkCodeInfo()
    {
        return $this->_markCodeInfo;
    }

    /**
     * Устанавливает код товара
     * @param array|MarkCodeInfo $value Код товара
     */
    public function setMarkCodeInfo($value)
    {
        if ($value === null || $value === '') {
            $this->_markCodeInfo = null;
        } else {
            if (is_array($value)) {
                $value = new MarkCodeInfo($value);
            }
            if (!($value instanceof MarkCodeInfo)) {
                throw new InvalidPropertyValueTypeException(
                    'Invalid markCodeInfo value type in ReceiptItem',
                    0,
                    'ReceiptItem.mark_code_info',
                    $value
                );
            }

            $this->_markCodeInfo = $value;
        }
    }

    /**
     * Возвращает меру количества предмета расчета
     * @return string Мера количества предмета расчета
     */
    public function getMeasure()
    {
        return $this->_measure;
    }

    /**
     * Устанавливает меру количества предмета расчета
     * @param string $value Мера количества предмета расчета
     */
    public function setMeasure($value)
    {
        if ($value === null || $value === '') {
            $this->_measure = null;
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid measure value type in ReceiptItem',
                0,
                'ReceiptItem.measure',
                $value
            );
        } elseif (!in_array($value, ReceiptItemMeasure::getEnabledValues())) {
            throw new InvalidPropertyValueException(
                'Invalid measure value in ReceiptItem',
                0,
                'ReceiptItem.measure',
                $value
            );
        } else {
            $this->_measure = $value;
        }
    }

    /**
     * Возвращает режим обработки кода маркировки
     * @return string Режим обработки кода маркировки
     */
    public function getMarkMode()
    {
        return $this->_markMode;
    }

    /**
     * Устанавливает режим обработки кода маркировки
     * @param string $value Режим обработки кода маркировки
     */
    public function setMarkMode($value)
    {
        if ($value === null || $value === '') {
            $this->_markMode = null;
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid markMode value type in ReceiptItem',
                0,
                'ReceiptItem.mark_mode',
                $value
            );
        } else {
            $this->_markMode = $value;
        }
    }

    /**
     * Возвращает дробное количество маркированного товара
     * @return MarkQuantity Дробное количество маркированного товара
     */
    public function getMarkQuantity()
    {
        return $this->_markQuantity;
    }

    /**
     * Устанавливает дробное количество маркированного товара
     * @param array|MarkQuantity $value Дробное количество маркированного товара
     */
    public function setMarkQuantity($value)
    {
        if ($value === null || $value === '') {
            $this->_markQuantity = null;
        } else {
            if (is_array($value)) {
                $value = new MarkQuantity($value);
            }
            if (!($value instanceof MarkQuantity)) {
                throw new InvalidPropertyValueTypeException(
                    'Invalid markQuantity value type in ReceiptItem',
                    0,
                    'ReceiptItem.mark_quantity',
                    $value
                );
            }

            $this->_markQuantity = $value;
        }
    }

    /**
     * Возвращает отраслевой реквизит чека
     * @return IndustryDetails[] Отраслевой реквизит чека
     */
    public function getPaymentSubjectIndustryDetails()
    {
        return $this->_paymentSubjectIndustryDetails;
    }

    /**
     * Устанавливает отраслевой реквизит чека
     * @param array|IndustryDetails[] $value Отраслевой реквизит чека
     */
    public function setPaymentSubjectIndustryDetails($value)
    {
        if ($value === null || $value === '') {
            $this->_paymentSubjectIndustryDetails = null;
            return $this;
        }
        if (!is_array($value) && !($value instanceof \Traversable)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid paymentSubjectIndustryDetails value type in ReceiptItem',
                0,
                'ReceiptItem.payment_subject_industry_details',
                $value
            );
        }
        $details = array();
        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $item = new IndustryDetails($item);
            }
            if ($item instanceof IndustryDetails) {
                $details[] = $item;
            } else {
                throw new InvalidPropertyValueTypeException(
                    'Invalid paymentSubjectIndustryDetails value type in ReceiptItem',
                    0,
                    'ReceiptItem.payment_subject_industry_details[' . $key . ']',
                    $item
                );
            }
        }
        $this->_paymentSubjectIndustryDetails = $details;

        return $this;
    }

    /**
     * Возвращает дополнительный реквизит предмета расчета
     * @return string Дополнительный реквизит предмета расчета
     */
    public function getAdditionalPaymentSubjectProps()
    {
        return $this->_additionalPaymentSubjectProps;
    }

    /**
     * Устанавливает дополнительный реквизит предмета расчета
     * @param string $value Дополнительный реквизит предмета расчета
     */
    public function setAdditionalPaymentSubjectProps($value)
    {
        if ($value === null || $value === '') {
            $this->_additionalPaymentSubjectProps = null;
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid additionalPaymentSubjectProps value type in ReceiptItem',
                0,
                'ReceiptItem.mark_mode',
                $value
            );
        } else {
            $this->_additionalPaymentSubjectProps = mb_substr($value, 0, self::ADD_PROPS_MAX_LENGTH);
        }
    }

    /**
     * Возвращает код страны происхождения товара по общероссийскому классификатору стран мира
     * @return string|null Код страны происхождения товара
     */
    public function getCountryOfOriginCode()
    {
        return $this->_countryOfOriginCode;
    }

    /**
     * Устанавливает код страны происхождения товара по общероссийскому классификатору стран мира
     *
     * @param string $value Код страны происхождения товара
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента была передана не строка
     */
    public function setCountryOfOriginCode($value)
    {
        if ($value === null || $value === '') {
            $this->_countryOfOriginCode = null;
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid countryOfOriginCode value type',
                0,
                'ReceiptItem.countryOfOriginCode',
                $value
            );
        } elseif (mb_strlen((string)$value) != 2) {
            throw new InvalidPropertyValueException(
                'Invalid countryOfOriginCode value: "' . $value . '"',
                0,
                'ReceiptItem.countryOfOriginCode',
                $value
            );
        } elseif (!preg_match('/^[A-Z]{2}$/', (string)$value)) {
            throw new InvalidPropertyValueException(
                'Invalid countryOfOriginCode value: "' . $value . '"',
                0,
                'ReceiptItem.countryOfOriginCode',
                $value
            );
        } else {
            $this->_countryOfOriginCode = $value;
        }
    }

    /**
     * Возвращает номер таможенной декларации
     * @return string|null Номер таможенной декларации (от 1 до 32 символов)
     */
    public function getCustomsDeclarationNumber()
    {
        return $this->_customsDeclarationNumber;
    }

    /**
     * Устанавливает номер таможенной декларации (от 1 до 32 символов)
     *
     * @param string $value Номер таможенной декларации
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента была передана не строка
     */
    public function setCustomsDeclarationNumber($value)
    {
        if ($value === null || $value === '') {
            $this->_customsDeclarationNumber = null;
        } elseif (!TypeCast::canCastToString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid customsDeclarationNumber value type',
                0,
                'ReceiptItem.customsDeclarationNumber',
                $value
            );
        } elseif (mb_strlen((string)$value) > 32) {
            throw new InvalidPropertyValueException(
                'Invalid customsDeclarationNumber value: "' . $value . '"',
                0,
                'ReceiptItem.customsDeclarationNumber',
                $value
            );
        } else {
            $this->_customsDeclarationNumber = $value;
        }
    }

    /**
     * Возвращает сумму акциза товара с учетом копеек
     * @return float|null Сумма акциза товара с учетом копеек
     */
    public function getExcise()
    {
        return $this->_excise;
    }

    /**
     * Устанавливает сумму акциза товара с учетом копеек
     *
     * @param float $value Сумма акциза товара с учетом копеек
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента было передано не число
     */
    public function setExcise($value)
    {
        if ($value === null || $value === '') {
            $this->_excise = null;
        } elseif (!is_numeric($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid excise value type',
                0,
                'ReceiptItem.excise',
                $value
            );
        } elseif ($value <= 0.0) {
            throw new InvalidPropertyValueException(
                'Invalid excise value in ReceiptItem',
                0,
                'ReceiptItem.excise',
                $value
            );
        } else {
            $this->_excise = $value;
        }
    }

    /**
     * Устанавливает флаг доставки для текущего объекта айтема в чеке
     *
     * @param bool $value True если айтем является доставкой, false если нет
     *
     * @return ReceiptItem
     * @throws InvalidPropertyValueException Генерируется если передано значение невалидного типа
     */
    public function setIsShipping($value)
    {
        if ($value === null || $value === '') {
            $this->_shipping = false;
        } elseif (TypeCast::canCastToBoolean($value)) {
            $this->_shipping = (bool)$value;
        } else {
            throw new InvalidPropertyValueException(
                'Invalid isShipping value in ReceiptItem',
                0,
                'ReceiptItem.isShipping',
                $value
            );
        }

        return $this;
    }

    /**
     * Возвращает информацию о поставщике товара или услуги.
     *
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->_supplier;
    }

    /**
     * Устанавливает информацию о поставщике товара или услуги.
     *
     * @param SupplierInterface|array $value Информация о поставщике товара или услуги
     * @return $this
     */
    public function setSupplier($value)
    {
        if ($value === null || $value === '') {
            $this->_supplier = null;
            return $this;
        }

        if (is_array($value)) {
            $value = new Supplier($value);
        }

        if (!($value instanceof SupplierInterface)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid supplier value type in ReceiptItem',
                0,
                'ReceiptItem.supplier',
                $value
            );
        }

        $this->_supplier = $value;

        return $this;
    }

    /**
     * Устанавливает тип посредника, реализующего товар или услугу
     * @param string $value Тип посредника
     */
    public function setAgentType($value)
    {
        if ($value === null || $value === '') {
            $this->_agentType = null;
        } elseif (!TypeCast::canCastToEnumString($value)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid value type for "agentType" parameter in ReceiptItem.agentType',
                0,
                'ReceiptItem.agentType',
                $value
            );
        } elseif (!AgentType::valueExists($value)) {
            throw new InvalidPropertyValueException(
                'Invalid value for "agentType" parameter in ReceiptItem.agentType',
                0,
                'ReceiptItem.agentType',
                $value
            );
        } else {
            $this->_agentType = $value;
        }
    }

    /**
     * Возвращает тип посредника, реализующего товар или услугу
     *
     * @return string Тип посредника
     */
    public function getAgentType()
    {
        return $this->_agentType;
    }

    /**
     * Проверяет, является ли текущий элемент чека доставкой
     *
     * @return bool True если доставка, false если обычный товар
     */
    public function isShipping()
    {
        return $this->_shipping;
    }

    /**
     * Применяет для товара скидку
     *
     * @param float $coefficient Множитель скидки
     */
    public function applyDiscountCoefficient($coefficient)
    {
        $this->_amount->multiply($coefficient);
    }

    /**
     * Увеличивает цену товара на указанную величину
     *
     * @param float $value Сумма на которую цену товара увеличиваем
     */
    public function increasePrice($value)
    {
        $this->_amount->increase($value);
    }

    /**
     * Уменьшает количество покупаемого товара на указанное, возвращает объект позиции в чеке с уменьшаемым количеством
     *
     * @param float $count Количество на которое уменьшаем позицию в чеке
     *
     * @return ReceiptItem
     *
     * @throws EmptyPropertyValueException Выбрасывается если было передано пустое значение
     * @throws InvalidPropertyValueException Выбрасывается если в качестве аргумента был передан ноль
     * или отрицательное число, или число больше текущего количества покупаемого товара
     * @throws InvalidPropertyValueTypeException Выбрасывается если в качестве аргумента было передано не число
     */
    public function fetchItem($count)
    {
        if ($count === null || $count === '') {
            throw new EmptyPropertyValueException(
                'Empty quantity value in ReceiptItem in fetchItem method',
                0,
                'ReceiptItem.quantity'
            );
        }
        if (!is_numeric($count)) {
            throw new InvalidPropertyValueTypeException(
                'Invalid quantity value type in ReceiptItem in fetchItem method',
                0,
                'ReceiptItem.quantity',
                $count
            );
        }
        if ($count <= 0.0 || $count >= $this->_quantity) {
            throw new InvalidPropertyValueException(
                'Invalid quantity value in ReceiptItem in fetchItem method',
                0,
                'ReceiptItem.quantity',
                $count
            );
        }

        $result = clone $this;
        $result->setPrice(clone $this->getPrice());
        $result->setQuantity($count);
        $this->_quantity -= $count;

        return $result;
    }

    /**
     * Устанавливает значения свойств текущего объекта из массива
     *
     * @param array|\Traversable $sourceArray Ассоциативный массив с настройками
     */
    public function fromArray($sourceArray)
    {
        if (isset($sourceArray['amount'])) {
            if (is_array($sourceArray['amount'])) {
                $sourceArray['price'] = new ReceiptItemAmount($sourceArray['amount']);
            } elseif ($sourceArray['amount'] instanceof AmountInterface) {
                $sourceArray['price'] = $sourceArray['amount'];
            }
            unset($sourceArray['amount']);
        }

        parent::fromArray($sourceArray);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $result = parent::jsonSerialize();

        $result['amount'] = $result['price'];
        unset($result['price']);

        return $result;
    }
}
