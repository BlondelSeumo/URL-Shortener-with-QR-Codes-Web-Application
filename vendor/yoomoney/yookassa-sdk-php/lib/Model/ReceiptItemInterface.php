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

use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\MarkCodeInfo;
use YooKassa\Model\Receipt\MarkQuantity;

/**
 * Interface ReceiptItemInterface
 *
 * @package YooKassa
 *
 * @property-read string $description Наименование товара (тег в 54 ФЗ — 1030)
 * @property-read float $quantity Количество (тег в 54 ФЗ — 1023)
 * @property-read string $measure Мера количества предмета расчета (тег в 54 ФЗ — 2108)
 * @property-read MarkQuantity $markQuantity Дробное количество маркированного товара (тег в 54 ФЗ — 1291)
 * @property-read MarkQuantity $mark_quantity Дробное количество маркированного товара (тег в 54 ФЗ — 1291)
 * @property-read float $amount Суммарная стоимость покупаемого товара в копейках/центах
 * @property-read AmountInterface $price Цена товара (тег в 54 ФЗ — 1079)
 * @property-read int $vatCode Ставка НДС, число 1-6 (тег в 54 ФЗ — 1199)
 * @property-read int $vat_code Ставка НДС, число 1-6 (тег в 54 ФЗ — 1199)
 * @property-read string $paymentSubject Признак предмета расчета (тег в 54 ФЗ — 1212)
 * @property-read string $payment_subject Признак предмета расчета (тег в 54 ФЗ — 1212)
 * @property-read string $paymentMode Признак способа расчета (тег в 54 ФЗ — 1214)
 * @property-read string $payment_mode Признак способа расчета (тег в 54 ФЗ — 1214)
 * @property-read string $countryOfOriginCode Код страны происхождения товара (тег в 54 ФЗ — 1230)
 * @property-read string $country_of_origin_code Код страны происхождения товара (тег в 54 ФЗ — 1230)
 * @property-read string $customsDeclarationNumber Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231
 * @property-read string $customs_declaration_number Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231
 * @property-read float $excise Сумма акциза товара с учетом копеек (тег в 54 ФЗ — 1229)
 * @property-read string $productCode Код товара — уникальный номер, который присваивается экземпляру товара при маркировке (тег в 54 ФЗ — 1162)
 * @property-read string $product_code Код товара — уникальный номер, который присваивается экземпляру товара при маркировке (тег в 54 ФЗ — 1162)
 * @property-read MarkCodeInfo $markCodeInfo Код товара (тег в 54 ФЗ — 1163)
 * @property-read MarkCodeInfo $mark_code_info Код товара (тег в 54 ФЗ — 1163)
 * @property-read string $markMode Режим обработки кода маркировки (тег в 54 ФЗ — 2102)
 * @property-read string $mark_mode Режим обработки кода маркировки (тег в 54 ФЗ — 2102)
 * @property-read IndustryDetails[] $paymentSubjectIndustryDetails Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260)
 * @property-read IndustryDetails[] $payment_subject_industry_details Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260)
 * @property-read string $additionalPaymentSubjectProps Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191)
 * @property-read string $additional_payment_subject_props Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191)
 * @property-read Supplier $supplier Информация о поставщике товара или услуги (тег в 54 ФЗ — 1224)
 * @property-read string $agentType Тип посредника, реализующего товар или услугу
 * @property-read string $agent_type Тип посредника, реализующего товар или услугу
 */
interface ReceiptItemInterface
{
    /**
     * Возвращает наименование товара
     * @return string Наименование товара
     */
    public function getDescription();

    /**
     * Возвращает количество товара
     * @return float Количество купленного товара
     */
    public function getQuantity();

    /**
     * Возвращает общую стоимость покупаемого товара в копейках/центах
     * @return float Сумма стоимости покупаемого товара
     */
    public function getAmount();

    /**
     * Возвращает цену товара
     * @return AmountInterface Цена товара
     */
    public function getPrice();

    /**
     * Возвращает ставку НДС
     * @return int|null Ставка НДС, число 1-6, или null, если ставка не задана
     */
    public function getVatCode();

    /**
     * Возвращает признак предмета расчета
     * @return string|null Признак предмета расчета
     */
    public function getPaymentSubject();

    /**
     * Возвращает признак способа расчета
     * @return string|null Признак способа расчета
     */
    public function getPaymentMode();

    /**
     * Возвращает код товара — уникальный номер, который присваивается экземпляру товара при маркировке
     * @return string|null Код товара
     */
    public function getProductCode();

    /**
     * Возвращает код товара
     * @return MarkCodeInfo Код товара
     */
    public function getMarkCodeInfo();

    /**
     * Возвращает меру количества предмета расчета
     * @return string Мера количества предмета расчета
     */
    public function getMeasure();

    /**
     * Возвращает режим обработки кода маркировки
     * @return string Режим обработки кода маркировки
     */
    public function getMarkMode();

    /**
     * Возвращает дробное количество маркированного товара
     * @return MarkQuantity Дробное количество маркированного товара
     */
    public function getMarkQuantity();

    /**
     * Возвращает отраслевой реквизит чека
     * @return IndustryDetails[] Отраслевой реквизит чека
     */
    public function getPaymentSubjectIndustryDetails();

    /**
     * Возвращает дополнительный реквизит предмета расчета
     * @return string Дополнительный реквизит предмета расчета
     */
    public function getAdditionalPaymentSubjectProps();

    /**
     * Возвращает код страны происхождения товара по общероссийскому классификатору стран мира
     * @return string|null Код страны происхождения товара
     */
    public function getCountryOfOriginCode();

    /**
     * Возвращает номер таможенной декларации
     * @return string|null Номер таможенной декларации (от 1 до 32 символов)
     */
    public function getCustomsDeclarationNumber();

    /**
     * Возвращает сумму акциза товара с учетом копеек
     * @return float|null Сумма акциза товара с учетом копеек
     */
    public function getExcise();

    /**
     * Возвращает информацию о поставщике товара или услуги
     * @return SupplierInterface Информация о поставщике товара или услуги
     */
    public function getSupplier();

    /**
     * Возвращает тип посредника, реализующего товар или услугу
     * @return string Тип посредника, реализующего товар или услугу.
     */
    public function getAgentType();

    /**
     * Проверяет, является ли текущий элемент чека доставкой
     * @return bool True если доставка, false если обычный товар
     */
    public function isShipping();
}
