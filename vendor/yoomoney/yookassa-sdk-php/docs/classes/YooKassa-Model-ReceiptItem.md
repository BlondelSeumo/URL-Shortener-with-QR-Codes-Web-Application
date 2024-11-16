# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Model\ReceiptItem
### Namespace: [\YooKassa\Model](../namespaces/yookassa-model.md)
---
**Summary:**

Информация о товарной позиции в заказе, позиция фискального чека


---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [DESCRIPTION_MAX_LENGTH](../classes/YooKassa-Model-ReceiptItem.md#constant_DESCRIPTION_MAX_LENGTH) |  |  |
| public | [ADD_PROPS_MAX_LENGTH](../classes/YooKassa-Model-ReceiptItem.md#constant_ADD_PROPS_MAX_LENGTH) |  |  |

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [$additional_payment_subject_props](../classes/YooKassa-Model-ReceiptItem.md#property_additional_payment_subject_props) |  | Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191) |
| public | [$additionalPaymentSubjectProps](../classes/YooKassa-Model-ReceiptItem.md#property_additionalPaymentSubjectProps) |  | Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191) |
| public | [$agent_type](../classes/YooKassa-Model-ReceiptItem.md#property_agent_type) |  | Тип посредника, реализующего товар или услугу |
| public | [$agentType](../classes/YooKassa-Model-ReceiptItem.md#property_agentType) |  | Тип посредника, реализующего товар или услугу |
| public | [$amount](../classes/YooKassa-Model-ReceiptItem.md#property_amount) |  | Суммарная стоимость покупаемого товара в копейках/центах |
| public | [$country_of_origin_code](../classes/YooKassa-Model-ReceiptItem.md#property_country_of_origin_code) |  | Код страны происхождения товара (тег в 54 ФЗ — 1230) |
| public | [$countryOfOriginCode](../classes/YooKassa-Model-ReceiptItem.md#property_countryOfOriginCode) |  | Код страны происхождения товара (тег в 54 ФЗ — 1230) |
| public | [$customs_declaration_number](../classes/YooKassa-Model-ReceiptItem.md#property_customs_declaration_number) |  | Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231 |
| public | [$customsDeclarationNumber](../classes/YooKassa-Model-ReceiptItem.md#property_customsDeclarationNumber) |  | Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231 |
| public | [$description](../classes/YooKassa-Model-ReceiptItem.md#property_description) |  | Наименование товара (тег в 54 ФЗ — 1030) |
| public | [$excise](../classes/YooKassa-Model-ReceiptItem.md#property_excise) |  | Сумма акциза товара с учетом копеек (тег в 54 ФЗ — 1229) |
| public | [$isShipping](../classes/YooKassa-Model-ReceiptItem.md#property_isShipping) |  | Флаг доставки |
| public | [$mark_code_info](../classes/YooKassa-Model-ReceiptItem.md#property_mark_code_info) |  | Код товара (тег в 54 ФЗ — 1163) |
| public | [$mark_mode](../classes/YooKassa-Model-ReceiptItem.md#property_mark_mode) |  | Режим обработки кода маркировки (тег в 54 ФЗ — 2102) |
| public | [$mark_quantity](../classes/YooKassa-Model-ReceiptItem.md#property_mark_quantity) |  | Дробное количество маркированного товара (тег в 54 ФЗ — 1291) |
| public | [$markCodeInfo](../classes/YooKassa-Model-ReceiptItem.md#property_markCodeInfo) |  | Код товара (тег в 54 ФЗ — 1163) |
| public | [$markMode](../classes/YooKassa-Model-ReceiptItem.md#property_markMode) |  | Режим обработки кода маркировки (тег в 54 ФЗ — 2102) |
| public | [$markQuantity](../classes/YooKassa-Model-ReceiptItem.md#property_markQuantity) |  | Дробное количество маркированного товара (тег в 54 ФЗ — 1291) |
| public | [$measure](../classes/YooKassa-Model-ReceiptItem.md#property_measure) |  | Мера количества предмета расчета (тег в 54 ФЗ — 2108) |
| public | [$payment_mode](../classes/YooKassa-Model-ReceiptItem.md#property_payment_mode) |  | Признак способа расчета (тег в 54 ФЗ — 1214) |
| public | [$payment_subject](../classes/YooKassa-Model-ReceiptItem.md#property_payment_subject) |  | Признак предмета расчета (тег в 54 ФЗ — 1212) |
| public | [$payment_subject_industry_details](../classes/YooKassa-Model-ReceiptItem.md#property_payment_subject_industry_details) |  | Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260) |
| public | [$paymentMode](../classes/YooKassa-Model-ReceiptItem.md#property_paymentMode) |  | Признак способа расчета (тег в 54 ФЗ — 1214) |
| public | [$paymentSubject](../classes/YooKassa-Model-ReceiptItem.md#property_paymentSubject) |  | Признак предмета расчета (тег в 54 ФЗ — 1212) |
| public | [$paymentSubjectIndustryDetails](../classes/YooKassa-Model-ReceiptItem.md#property_paymentSubjectIndustryDetails) |  | Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260) |
| public | [$price](../classes/YooKassa-Model-ReceiptItem.md#property_price) |  | Цена товара (тег в 54 ФЗ — 1079) |
| public | [$product_code](../classes/YooKassa-Model-ReceiptItem.md#property_product_code) |  | Код товара (тег в 54 ФЗ — 1162) |
| public | [$productCode](../classes/YooKassa-Model-ReceiptItem.md#property_productCode) |  | Код товара (тег в 54 ФЗ — 1162) |
| public | [$quantity](../classes/YooKassa-Model-ReceiptItem.md#property_quantity) |  | Количество (тег в 54 ФЗ — 1023) |
| public | [$supplier](../classes/YooKassa-Model-ReceiptItem.md#property_supplier) |  | Информация о поставщике товара или услуги (тег в 54 ФЗ — 1224) |
| public | [$vat_code](../classes/YooKassa-Model-ReceiptItem.md#property_vat_code) |  | Ставка НДС (тег в 54 ФЗ — 1199), число 1-6 |
| public | [$vatCode](../classes/YooKassa-Model-ReceiptItem.md#property_vatCode) |  | Ставка НДС (тег в 54 ФЗ — 1199), число 1-6 |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [__construct()](../classes/YooKassa-Common-AbstractObject.md#method___construct) |  | AbstractObject constructor. |
| public | [__get()](../classes/YooKassa-Common-AbstractObject.md#method___get) |  | Возвращает значение свойства |
| public | [__isset()](../classes/YooKassa-Common-AbstractObject.md#method___isset) |  | Проверяет наличие свойства |
| public | [__set()](../classes/YooKassa-Common-AbstractObject.md#method___set) |  | Устанавливает значение свойства |
| public | [__unset()](../classes/YooKassa-Common-AbstractObject.md#method___unset) |  | Удаляет свойство |
| public | [applyDiscountCoefficient()](../classes/YooKassa-Model-ReceiptItem.md#method_applyDiscountCoefficient) |  | Применяет для товара скидку |
| public | [fetchItem()](../classes/YooKassa-Model-ReceiptItem.md#method_fetchItem) |  | Уменьшает количество покупаемого товара на указанное, возвращает объект позиции в чеке с уменьшаемым количеством |
| public | [fromArray()](../classes/YooKassa-Model-ReceiptItem.md#method_fromArray) |  | Устанавливает значения свойств текущего объекта из массива |
| public | [getAdditionalPaymentSubjectProps()](../classes/YooKassa-Model-ReceiptItem.md#method_getAdditionalPaymentSubjectProps) |  | Возвращает дополнительный реквизит предмета расчета |
| public | [getAgentType()](../classes/YooKassa-Model-ReceiptItem.md#method_getAgentType) |  | Возвращает тип посредника, реализующего товар или услугу |
| public | [getAmount()](../classes/YooKassa-Model-ReceiptItem.md#method_getAmount) |  | Возвращает общую стоимость покупаемого товара в копейках/центах |
| public | [getCountryOfOriginCode()](../classes/YooKassa-Model-ReceiptItem.md#method_getCountryOfOriginCode) |  | Возвращает код страны происхождения товара по общероссийскому классификатору стран мира |
| public | [getCustomsDeclarationNumber()](../classes/YooKassa-Model-ReceiptItem.md#method_getCustomsDeclarationNumber) |  | Возвращает номер таможенной декларации |
| public | [getDescription()](../classes/YooKassa-Model-ReceiptItem.md#method_getDescription) |  | Возвращает наименование товара |
| public | [getExcise()](../classes/YooKassa-Model-ReceiptItem.md#method_getExcise) |  | Возвращает сумму акциза товара с учетом копеек |
| public | [getMarkCodeInfo()](../classes/YooKassa-Model-ReceiptItem.md#method_getMarkCodeInfo) |  | Возвращает код товара |
| public | [getMarkMode()](../classes/YooKassa-Model-ReceiptItem.md#method_getMarkMode) |  | Возвращает режим обработки кода маркировки |
| public | [getMarkQuantity()](../classes/YooKassa-Model-ReceiptItem.md#method_getMarkQuantity) |  | Возвращает дробное количество маркированного товара |
| public | [getMeasure()](../classes/YooKassa-Model-ReceiptItem.md#method_getMeasure) |  | Возвращает меру количества предмета расчета |
| public | [getPaymentMode()](../classes/YooKassa-Model-ReceiptItem.md#method_getPaymentMode) |  | Возвращает признак способа расчета |
| public | [getPaymentSubject()](../classes/YooKassa-Model-ReceiptItem.md#method_getPaymentSubject) |  | Возвращает признак предмета расчета |
| public | [getPaymentSubjectIndustryDetails()](../classes/YooKassa-Model-ReceiptItem.md#method_getPaymentSubjectIndustryDetails) |  | Возвращает отраслевой реквизит чека |
| public | [getPrice()](../classes/YooKassa-Model-ReceiptItem.md#method_getPrice) |  | Возвращает цену товара |
| public | [getProductCode()](../classes/YooKassa-Model-ReceiptItem.md#method_getProductCode) |  | Возвращает код товара — уникальный номер, который присваивается экземпляру товара при маркировке |
| public | [getQuantity()](../classes/YooKassa-Model-ReceiptItem.md#method_getQuantity) |  | Возвращает количество товара |
| public | [getSupplier()](../classes/YooKassa-Model-ReceiptItem.md#method_getSupplier) |  | Возвращает информацию о поставщике товара или услуги. |
| public | [getVatCode()](../classes/YooKassa-Model-ReceiptItem.md#method_getVatCode) |  | Возвращает ставку НДС |
| public | [increasePrice()](../classes/YooKassa-Model-ReceiptItem.md#method_increasePrice) |  | Увеличивает цену товара на указанную величину |
| public | [isShipping()](../classes/YooKassa-Model-ReceiptItem.md#method_isShipping) |  | Проверяет, является ли текущий элемент чека доставкой |
| public | [jsonSerialize()](../classes/YooKassa-Model-ReceiptItem.md#method_jsonSerialize) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации |
| public | [offsetExists()](../classes/YooKassa-Common-AbstractObject.md#method_offsetExists) |  | Проверяет наличие свойства |
| public | [offsetGet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetGet) |  | Возвращает значение свойства |
| public | [offsetSet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetSet) |  | Устанавливает значение свойства |
| public | [offsetUnset()](../classes/YooKassa-Common-AbstractObject.md#method_offsetUnset) |  | Удаляет свойство |
| public | [setAdditionalPaymentSubjectProps()](../classes/YooKassa-Model-ReceiptItem.md#method_setAdditionalPaymentSubjectProps) |  | Устанавливает дополнительный реквизит предмета расчета |
| public | [setAgentType()](../classes/YooKassa-Model-ReceiptItem.md#method_setAgentType) |  | Устанавливает тип посредника, реализующего товар или услугу |
| public | [setCountryOfOriginCode()](../classes/YooKassa-Model-ReceiptItem.md#method_setCountryOfOriginCode) |  | Устанавливает код страны происхождения товара по общероссийскому классификатору стран мира |
| public | [setCustomsDeclarationNumber()](../classes/YooKassa-Model-ReceiptItem.md#method_setCustomsDeclarationNumber) |  | Устанавливает номер таможенной декларации (от 1 до 32 символов) |
| public | [setDescription()](../classes/YooKassa-Model-ReceiptItem.md#method_setDescription) |  | Устанавливает наименование товара |
| public | [setExcise()](../classes/YooKassa-Model-ReceiptItem.md#method_setExcise) |  | Устанавливает сумму акциза товара с учетом копеек |
| public | [setIsShipping()](../classes/YooKassa-Model-ReceiptItem.md#method_setIsShipping) |  | Устанавливает флаг доставки для текущего объекта айтема в чеке |
| public | [setMarkCodeInfo()](../classes/YooKassa-Model-ReceiptItem.md#method_setMarkCodeInfo) |  | Устанавливает код товара |
| public | [setMarkMode()](../classes/YooKassa-Model-ReceiptItem.md#method_setMarkMode) |  | Устанавливает режим обработки кода маркировки |
| public | [setMarkQuantity()](../classes/YooKassa-Model-ReceiptItem.md#method_setMarkQuantity) |  | Устанавливает дробное количество маркированного товара |
| public | [setMeasure()](../classes/YooKassa-Model-ReceiptItem.md#method_setMeasure) |  | Устанавливает меру количества предмета расчета |
| public | [setPaymentMode()](../classes/YooKassa-Model-ReceiptItem.md#method_setPaymentMode) |  | Устанавливает признак способа расчета |
| public | [setPaymentSubject()](../classes/YooKassa-Model-ReceiptItem.md#method_setPaymentSubject) |  | Устанавливает признак предмета расчета |
| public | [setPaymentSubjectIndustryDetails()](../classes/YooKassa-Model-ReceiptItem.md#method_setPaymentSubjectIndustryDetails) |  | Устанавливает отраслевой реквизит чека |
| public | [setPrice()](../classes/YooKassa-Model-ReceiptItem.md#method_setPrice) |  | Устанавливает цену товара |
| public | [setProductCode()](../classes/YooKassa-Model-ReceiptItem.md#method_setProductCode) |  | Устанавливает код товара — уникальный номер, который присваивается экземпляру товара при маркировке |
| public | [setQuantity()](../classes/YooKassa-Model-ReceiptItem.md#method_setQuantity) |  | Устанавливает количество покупаемого товара |
| public | [setSupplier()](../classes/YooKassa-Model-ReceiptItem.md#method_setSupplier) |  | Устанавливает информацию о поставщике товара или услуги. |
| public | [setVatCode()](../classes/YooKassa-Model-ReceiptItem.md#method_setVatCode) |  | Устанавливает ставку НДС |
| public | [toArray()](../classes/YooKassa-Common-AbstractObject.md#method_toArray) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации Является алиасом метода AbstractObject::jsonSerialize() |
| protected | [getUnknownProperties()](../classes/YooKassa-Common-AbstractObject.md#method_getUnknownProperties) |  | Возвращает массив свойств которые не существуют, но были заданы у объекта |

---
### Details
* File: [lib/Model/ReceiptItem.php](../../lib/Model/ReceiptItem.php)
* Package: Default
* Class Hierarchy: 
  * [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)
  * \YooKassa\Model\ReceiptItem
* Implements:
  * [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

---
## Constants
<a name="constant_DESCRIPTION_MAX_LENGTH" class="anchor"></a>
###### DESCRIPTION_MAX_LENGTH
```php
DESCRIPTION_MAX_LENGTH = 128 : int
```


<a name="constant_ADD_PROPS_MAX_LENGTH" class="anchor"></a>
###### ADD_PROPS_MAX_LENGTH
```php
ADD_PROPS_MAX_LENGTH = 64 : int
```



---
## Properties
<a name="property_additional_payment_subject_props"></a>
#### public $additional_payment_subject_props : string
---
***Description***

Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_additionalPaymentSubjectProps"></a>
#### public $additionalPaymentSubjectProps : string
---
***Description***

Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_agent_type"></a>
#### public $agent_type : string
---
***Description***

Тип посредника, реализующего товар или услугу

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_agentType"></a>
#### public $agentType : string
---
***Description***

Тип посредника, реализующего товар или услугу

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_amount"></a>
#### public $amount : float
---
***Description***

Суммарная стоимость покупаемого товара в копейках/центах

**Type:** <a href="../float"><abbr title="float">float</abbr></a>

**Details:**


<a name="property_country_of_origin_code"></a>
#### public $country_of_origin_code : string
---
***Description***

Код страны происхождения товара (тег в 54 ФЗ — 1230)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_countryOfOriginCode"></a>
#### public $countryOfOriginCode : string
---
***Description***

Код страны происхождения товара (тег в 54 ФЗ — 1230)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_customs_declaration_number"></a>
#### public $customs_declaration_number : string
---
***Description***

Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_customsDeclarationNumber"></a>
#### public $customsDeclarationNumber : string
---
***Description***

Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_description"></a>
#### public $description : string
---
***Description***

Наименование товара (тег в 54 ФЗ — 1030)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_excise"></a>
#### public $excise : float
---
***Description***

Сумма акциза товара с учетом копеек (тег в 54 ФЗ — 1229)

**Type:** <a href="../float"><abbr title="float">float</abbr></a>

**Details:**


<a name="property_isShipping"></a>
#### public $isShipping : bool
---
***Description***

Флаг доставки

**Type:** <a href="../bool"><abbr title="bool">bool</abbr></a>

**Details:**


<a name="property_mark_code_info"></a>
#### public $mark_code_info : \YooKassa\Model\Receipt\MarkCodeInfo
---
***Description***

Код товара (тег в 54 ФЗ — 1163)

**Type:** <a href="../classes/YooKassa-Model-Receipt-MarkCodeInfo.html"><abbr title="\YooKassa\Model\Receipt\MarkCodeInfo">MarkCodeInfo</abbr></a>

**Details:**


<a name="property_mark_mode"></a>
#### public $mark_mode : string
---
***Description***

Режим обработки кода маркировки (тег в 54 ФЗ — 2102)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_mark_quantity"></a>
#### public $mark_quantity : \YooKassa\Model\Receipt\MarkQuantity
---
***Description***

Дробное количество маркированного товара (тег в 54 ФЗ — 1291)

**Type:** <a href="../classes/YooKassa-Model-Receipt-MarkQuantity.html"><abbr title="\YooKassa\Model\Receipt\MarkQuantity">MarkQuantity</abbr></a>

**Details:**


<a name="property_markCodeInfo"></a>
#### public $markCodeInfo : \YooKassa\Model\Receipt\MarkCodeInfo
---
***Description***

Код товара (тег в 54 ФЗ — 1163)

**Type:** <a href="../classes/YooKassa-Model-Receipt-MarkCodeInfo.html"><abbr title="\YooKassa\Model\Receipt\MarkCodeInfo">MarkCodeInfo</abbr></a>

**Details:**


<a name="property_markMode"></a>
#### public $markMode : string
---
***Description***

Режим обработки кода маркировки (тег в 54 ФЗ — 2102)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_markQuantity"></a>
#### public $markQuantity : \YooKassa\Model\Receipt\MarkQuantity
---
***Description***

Дробное количество маркированного товара (тег в 54 ФЗ — 1291)

**Type:** <a href="../classes/YooKassa-Model-Receipt-MarkQuantity.html"><abbr title="\YooKassa\Model\Receipt\MarkQuantity">MarkQuantity</abbr></a>

**Details:**


<a name="property_measure"></a>
#### public $measure : string
---
***Description***

Мера количества предмета расчета (тег в 54 ФЗ — 2108)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_payment_mode"></a>
#### public $payment_mode : string
---
***Description***

Признак способа расчета (тег в 54 ФЗ — 1214)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_payment_subject"></a>
#### public $payment_subject : string
---
***Description***

Признак предмета расчета (тег в 54 ФЗ — 1212)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_payment_subject_industry_details"></a>
#### public $payment_subject_industry_details : \YooKassa\Model\Receipt\IndustryDetails[]
---
***Description***

Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260)

**Type:** <a href="../\YooKassa\Model\Receipt\IndustryDetails[]"><abbr title="\YooKassa\Model\Receipt\IndustryDetails[]">IndustryDetails[]</abbr></a>

**Details:**


<a name="property_paymentMode"></a>
#### public $paymentMode : string
---
***Description***

Признак способа расчета (тег в 54 ФЗ — 1214)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_paymentSubject"></a>
#### public $paymentSubject : string
---
***Description***

Признак предмета расчета (тег в 54 ФЗ — 1212)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_paymentSubjectIndustryDetails"></a>
#### public $paymentSubjectIndustryDetails : \YooKassa\Model\Receipt\IndustryDetails[]
---
***Description***

Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260)

**Type:** <a href="../\YooKassa\Model\Receipt\IndustryDetails[]"><abbr title="\YooKassa\Model\Receipt\IndustryDetails[]">IndustryDetails[]</abbr></a>

**Details:**


<a name="property_price"></a>
#### public $price : \YooKassa\Model\AmountInterface
---
***Description***

Цена товара (тег в 54 ФЗ — 1079)

**Type:** <a href="../classes/YooKassa-Model-AmountInterface.html"><abbr title="\YooKassa\Model\AmountInterface">AmountInterface</abbr></a>

**Details:**


<a name="property_product_code"></a>
#### public $product_code : string
---
***Description***

Код товара (тег в 54 ФЗ — 1162)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_productCode"></a>
#### public $productCode : string
---
***Description***

Код товара (тег в 54 ФЗ — 1162)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_quantity"></a>
#### public $quantity : float
---
***Description***

Количество (тег в 54 ФЗ — 1023)

**Type:** <a href="../float"><abbr title="float">float</abbr></a>

**Details:**


<a name="property_supplier"></a>
#### public $supplier : \YooKassa\Model\Supplier
---
***Description***

Информация о поставщике товара или услуги (тег в 54 ФЗ — 1224)

**Type:** <a href="../classes/YooKassa-Model-Supplier.html"><abbr title="\YooKassa\Model\Supplier">Supplier</abbr></a>

**Details:**


<a name="property_vat_code"></a>
#### public $vat_code : int
---
***Description***

Ставка НДС (тег в 54 ФЗ — 1199), число 1-6

**Type:** <a href="../int"><abbr title="int">int</abbr></a>

**Details:**


<a name="property_vatCode"></a>
#### public $vatCode : int
---
***Description***

Ставка НДС (тег в 54 ФЗ — 1199), число 1-6

**Type:** <a href="../int"><abbr title="int">int</abbr></a>

**Details:**



---
## Methods
<a name="method___construct" class="anchor"></a>
#### public __construct() : mixed

```php
public __construct(array $data = array()) : mixed
```

**Summary**

AbstractObject constructor.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array</code> | data  |  |

**Returns:** mixed - 


<a name="method___get" class="anchor"></a>
#### public __get() : mixed

```php
public __get(string $propertyName) : mixed
```

**Summary**

Возвращает значение свойства

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  | Имя свойства |

**Returns:** mixed - Значение свойства


<a name="method___isset" class="anchor"></a>
#### public __isset() : bool

```php
public __isset(string $propertyName) : bool
```

**Summary**

Проверяет наличие свойства

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  | Имя проверяемого свойства |

**Returns:** bool - True если свойство имеется, false если нет


<a name="method___set" class="anchor"></a>
#### public __set() : mixed

```php
public __set(string $propertyName, mixed $value) : mixed
```

**Summary**

Устанавливает значение свойства

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  | Имя свойства |
| <code lang="php">mixed</code> | value  | Значение свойства |

**Returns:** mixed - 


<a name="method___unset" class="anchor"></a>
#### public __unset() : mixed

```php
public __unset(string $propertyName) : mixed
```

**Summary**

Удаляет свойство

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  | Имя удаляемого свойства |

**Returns:** mixed - 


<a name="method_applyDiscountCoefficient" class="anchor"></a>
#### public applyDiscountCoefficient() : mixed

```php
public applyDiscountCoefficient(float $coefficient) : mixed
```

**Summary**

Применяет для товара скидку

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">float</code> | coefficient  | Множитель скидки |

**Returns:** mixed - 


<a name="method_fetchItem" class="anchor"></a>
#### public fetchItem() : \YooKassa\Model\ReceiptItem

```php
public fetchItem(float $count) : \YooKassa\Model\ReceiptItem
```

**Summary**

Уменьшает количество покупаемого товара на указанное, возвращает объект позиции в чеке с уменьшаемым количеством

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">float</code> | count  | Количество на которое уменьшаем позицию в чеке |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\EmptyPropertyValueException | Выбрасывается если было передано пустое значение |
| \YooKassa\Common\Exceptions\InvalidPropertyValueException | Выбрасывается если в качестве аргумента был передан ноль или отрицательное число, или число больше текущего количества покупаемого товара |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента было передано не число |

**Returns:** \YooKassa\Model\ReceiptItem - 


<a name="method_fromArray" class="anchor"></a>
#### public fromArray() : mixed

```php
public fromArray(array|\Traversable $sourceArray) : mixed
```

**Summary**

Устанавливает значения свойств текущего объекта из массива

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \Traversable</code> | sourceArray  | Ассоциативный массив с настройками |

**Returns:** mixed - 


<a name="method_getAdditionalPaymentSubjectProps" class="anchor"></a>
#### public getAdditionalPaymentSubjectProps() : string

```php
public getAdditionalPaymentSubjectProps() : string
```

**Summary**

Возвращает дополнительный реквизит предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string - Дополнительный реквизит предмета расчета


<a name="method_getAgentType" class="anchor"></a>
#### public getAgentType() : string

```php
public getAgentType() : string
```

**Summary**

Возвращает тип посредника, реализующего товар или услугу

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string - Тип посредника


<a name="method_getAmount" class="anchor"></a>
#### public getAmount() : int

```php
public getAmount() : int
```

**Summary**

Возвращает общую стоимость покупаемого товара в копейках/центах

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** int - Сумма стоимости покупаемого товара


<a name="method_getCountryOfOriginCode" class="anchor"></a>
#### public getCountryOfOriginCode() : string|null

```php
public getCountryOfOriginCode() : string|null
```

**Summary**

Возвращает код страны происхождения товара по общероссийскому классификатору стран мира

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string|null - Код страны происхождения товара


<a name="method_getCustomsDeclarationNumber" class="anchor"></a>
#### public getCustomsDeclarationNumber() : string|null

```php
public getCustomsDeclarationNumber() : string|null
```

**Summary**

Возвращает номер таможенной декларации

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string|null - Номер таможенной декларации (от 1 до 32 символов)


<a name="method_getDescription" class="anchor"></a>
#### public getDescription() : string

```php
public getDescription() : string
```

**Summary**

Возвращает наименование товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string - Наименование товара


<a name="method_getExcise" class="anchor"></a>
#### public getExcise() : float|null

```php
public getExcise() : float|null
```

**Summary**

Возвращает сумму акциза товара с учетом копеек

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** float|null - Сумма акциза товара с учетом копеек


<a name="method_getMarkCodeInfo" class="anchor"></a>
#### public getMarkCodeInfo() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public getMarkCodeInfo() : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Summary**

Возвращает код товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - Код товара


<a name="method_getMarkMode" class="anchor"></a>
#### public getMarkMode() : string

```php
public getMarkMode() : string
```

**Summary**

Возвращает режим обработки кода маркировки

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string - Режим обработки кода маркировки


<a name="method_getMarkQuantity" class="anchor"></a>
#### public getMarkQuantity() : \YooKassa\Model\Receipt\MarkQuantity

```php
public getMarkQuantity() : \YooKassa\Model\Receipt\MarkQuantity
```

**Summary**

Возвращает дробное количество маркированного товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** \YooKassa\Model\Receipt\MarkQuantity - Дробное количество маркированного товара


<a name="method_getMeasure" class="anchor"></a>
#### public getMeasure() : string

```php
public getMeasure() : string
```

**Summary**

Возвращает меру количества предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string - Мера количества предмета расчета


<a name="method_getPaymentMode" class="anchor"></a>
#### public getPaymentMode() : string|null

```php
public getPaymentMode() : string|null
```

**Summary**

Возвращает признак способа расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string|null - Признак способа расчета


<a name="method_getPaymentSubject" class="anchor"></a>
#### public getPaymentSubject() : string|null

```php
public getPaymentSubject() : string|null
```

**Summary**

Возвращает признак предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string|null - Признак предмета расчета


<a name="method_getPaymentSubjectIndustryDetails" class="anchor"></a>
#### public getPaymentSubjectIndustryDetails() : \YooKassa\Model\Receipt\IndustryDetails[]

```php
public getPaymentSubjectIndustryDetails() : \YooKassa\Model\Receipt\IndustryDetails[]
```

**Summary**

Возвращает отраслевой реквизит чека

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** \YooKassa\Model\Receipt\IndustryDetails[] - Отраслевой реквизит чека


<a name="method_getPrice" class="anchor"></a>
#### public getPrice() : \YooKassa\Model\AmountInterface

```php
public getPrice() : \YooKassa\Model\AmountInterface
```

**Summary**

Возвращает цену товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** \YooKassa\Model\AmountInterface - Цена товара


<a name="method_getProductCode" class="anchor"></a>
#### public getProductCode() : string|null

```php
public getProductCode() : string|null
```

**Summary**

Возвращает код товара — уникальный номер, который присваивается экземпляру товара при маркировке

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** string|null - Код товара


<a name="method_getQuantity" class="anchor"></a>
#### public getQuantity() : float

```php
public getQuantity() : float
```

**Summary**

Возвращает количество товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** float - Количество купленного товара


<a name="method_getSupplier" class="anchor"></a>
#### public getSupplier() : \YooKassa\Model\Supplier

```php
public getSupplier() : \YooKassa\Model\Supplier
```

**Summary**

Возвращает информацию о поставщике товара или услуги.

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** \YooKassa\Model\Supplier - 


<a name="method_getVatCode" class="anchor"></a>
#### public getVatCode() : int|null

```php
public getVatCode() : int|null
```

**Summary**

Возвращает ставку НДС

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** int|null - Ставка НДС, число 1-6, или null, если ставка не задана


<a name="method_increasePrice" class="anchor"></a>
#### public increasePrice() : mixed

```php
public increasePrice(float $value) : mixed
```

**Summary**

Увеличивает цену товара на указанную величину

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">float</code> | value  | Сумма на которую цену товара увеличиваем |

**Returns:** mixed - 


<a name="method_isShipping" class="anchor"></a>
#### public isShipping() : bool

```php
public isShipping() : bool
```

**Summary**

Проверяет, является ли текущий элемент чека доставкой

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** bool - True если доставка, false если обычный товар


<a name="method_jsonSerialize" class="anchor"></a>
#### public jsonSerialize() : array

```php
public jsonSerialize() : array
```

**Summary**

Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

**Returns:** array - 

##### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| inheritdoc |  |  |

<a name="method_offsetExists" class="anchor"></a>
#### public offsetExists() : bool

```php
public offsetExists(string $offset) : bool
```

**Summary**

Проверяет наличие свойства

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | offset  | Имя проверяемого свойства |

**Returns:** bool - True если свойство имеется, false если нет


<a name="method_offsetGet" class="anchor"></a>
#### public offsetGet() : mixed

```php
public offsetGet(string $offset) : mixed
```

**Summary**

Возвращает значение свойства

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | offset  | Имя свойства |

**Returns:** mixed - Значение свойства


<a name="method_offsetSet" class="anchor"></a>
#### public offsetSet() : void

```php
public offsetSet(string $offset, mixed $value) : void
```

**Summary**

Устанавливает значение свойства

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | offset  | Имя свойства |
| <code lang="php">mixed</code> | value  | Значение свойства |

**Returns:** void - 


<a name="method_offsetUnset" class="anchor"></a>
#### public offsetUnset() : void

```php
public offsetUnset(string $offset) : void
```

**Summary**

Удаляет свойство

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | offset  | Имя удаляемого свойства |

**Returns:** void - 


<a name="method_setAdditionalPaymentSubjectProps" class="anchor"></a>
#### public setAdditionalPaymentSubjectProps() : mixed

```php
public setAdditionalPaymentSubjectProps(string $value) : mixed
```

**Summary**

Устанавливает дополнительный реквизит предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Дополнительный реквизит предмета расчета |

**Returns:** mixed - 


<a name="method_setAgentType" class="anchor"></a>
#### public setAgentType() : mixed

```php
public setAgentType(string $value) : mixed
```

**Summary**

Устанавливает тип посредника, реализующего товар или услугу

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Тип посредника |

**Returns:** mixed - 


<a name="method_setCountryOfOriginCode" class="anchor"></a>
#### public setCountryOfOriginCode() : mixed

```php
public setCountryOfOriginCode(string $value) : mixed
```

**Summary**

Устанавливает код страны происхождения товара по общероссийскому классификатору стран мира

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Код страны происхождения товара |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента была передана не строка |

**Returns:** mixed - 


<a name="method_setCustomsDeclarationNumber" class="anchor"></a>
#### public setCustomsDeclarationNumber() : mixed

```php
public setCustomsDeclarationNumber(string $value) : mixed
```

**Summary**

Устанавливает номер таможенной декларации (от 1 до 32 символов)

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Номер таможенной декларации |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента была передана не строка |

**Returns:** mixed - 


<a name="method_setDescription" class="anchor"></a>
#### public setDescription() : mixed

```php
public setDescription(string $value) : mixed
```

**Summary**

Устанавливает наименование товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Наименование товара |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\EmptyPropertyValueException | Выбрасывается если было передано пустое значение |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента была передана не строка |

**Returns:** mixed - 


<a name="method_setExcise" class="anchor"></a>
#### public setExcise() : mixed

```php
public setExcise(float $value) : mixed
```

**Summary**

Устанавливает сумму акциза товара с учетом копеек

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">float</code> | value  | Сумма акциза товара с учетом копеек |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента было передано не число |

**Returns:** mixed - 


<a name="method_setIsShipping" class="anchor"></a>
#### public setIsShipping() : \YooKassa\Model\ReceiptItem

```php
public setIsShipping(bool $value) : \YooKassa\Model\ReceiptItem
```

**Summary**

Устанавливает флаг доставки для текущего объекта айтема в чеке

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">bool</code> | value  | True если айтем является доставкой, false если нет |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueException | Генерируется если передано значение невалидного типа |

**Returns:** \YooKassa\Model\ReceiptItem - 


<a name="method_setMarkCodeInfo" class="anchor"></a>
#### public setMarkCodeInfo() : mixed

```php
public setMarkCodeInfo(array|\YooKassa\Model\Receipt\MarkCodeInfo $value) : mixed
```

**Summary**

Устанавливает код товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \YooKassa\Model\Receipt\MarkCodeInfo</code> | value  | Код товара |

**Returns:** mixed - 


<a name="method_setMarkMode" class="anchor"></a>
#### public setMarkMode() : mixed

```php
public setMarkMode(string $value) : mixed
```

**Summary**

Устанавливает режим обработки кода маркировки

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Режим обработки кода маркировки |

**Returns:** mixed - 


<a name="method_setMarkQuantity" class="anchor"></a>
#### public setMarkQuantity() : mixed

```php
public setMarkQuantity(array|\YooKassa\Model\Receipt\MarkQuantity $value) : mixed
```

**Summary**

Устанавливает дробное количество маркированного товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \YooKassa\Model\Receipt\MarkQuantity</code> | value  | Дробное количество маркированного товара |

**Returns:** mixed - 


<a name="method_setMeasure" class="anchor"></a>
#### public setMeasure() : mixed

```php
public setMeasure(string $value) : mixed
```

**Summary**

Устанавливает меру количества предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Мера количества предмета расчета |

**Returns:** mixed - 


<a name="method_setPaymentMode" class="anchor"></a>
#### public setPaymentMode() : mixed

```php
public setPaymentMode(string $value) : mixed
```

**Summary**

Устанавливает признак способа расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Признак способа расчета |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента была передана не строка |

**Returns:** mixed - 


<a name="method_setPaymentSubject" class="anchor"></a>
#### public setPaymentSubject() : mixed

```php
public setPaymentSubject(string $value) : mixed
```

**Summary**

Устанавливает признак предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Признак предмета расчета |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента была передана не строка |

**Returns:** mixed - 


<a name="method_setPaymentSubjectIndustryDetails" class="anchor"></a>
#### public setPaymentSubjectIndustryDetails() : mixed

```php
public setPaymentSubjectIndustryDetails(array|\YooKassa\Model\Receipt\IndustryDetails[] $value) : mixed
```

**Summary**

Устанавливает отраслевой реквизит чека

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \YooKassa\Model\Receipt\IndustryDetails[]</code> | value  | Отраслевой реквизит чека |

**Returns:** mixed - 


<a name="method_setPrice" class="anchor"></a>
#### public setPrice() : mixed

```php
public setPrice(\YooKassa\Model\AmountInterface|array $value) : mixed
```

**Summary**

Устанавливает цену товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\AmountInterface OR array</code> | value  | Цена товара |

**Returns:** mixed - 


<a name="method_setProductCode" class="anchor"></a>
#### public setProductCode() : mixed

```php
public setProductCode(string|\YooKassa\Helpers\ProductCode $value) : mixed
```

**Summary**

Устанавливает код товара — уникальный номер, который присваивается экземпляру товара при маркировке

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR \YooKassa\Helpers\ProductCode</code> | value  | Код товара |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента была передана не строка |

**Returns:** mixed - 


<a name="method_setQuantity" class="anchor"></a>
#### public setQuantity() : mixed

```php
public setQuantity(int $value) : mixed
```

**Summary**

Устанавливает количество покупаемого товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">int</code> | value  | Количество |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\EmptyPropertyValueException | Выбрасывается если было передано пустое значение |
| \YooKassa\Common\Exceptions\InvalidPropertyValueException | Выбрасывается если в качестве аргумента был передан ноль или отрицательное число |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента было передано не число |

**Returns:** mixed - 


<a name="method_setSupplier" class="anchor"></a>
#### public setSupplier() : $this

```php
public setSupplier(\YooKassa\Model\SupplierInterface|array $value) : $this
```

**Summary**

Устанавливает информацию о поставщике товара или услуги.

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\SupplierInterface OR array</code> | value  | Информация о поставщике товара или услуги |

**Returns:** $this - 


<a name="method_setVatCode" class="anchor"></a>
#### public setVatCode() : mixed

```php
public setVatCode(int $value) : mixed
```

**Summary**

Устанавливает ставку НДС

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItem](../classes/YooKassa-Model-ReceiptItem.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">int</code> | value  | Ставка НДС, число 1-6 |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueException | Выбрасывается если в качестве аргумента было передано число меньше одного или больше шести |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если в качестве аргумента было передано не число |

**Returns:** mixed - 


<a name="method_toArray" class="anchor"></a>
#### public toArray() : array

```php
public toArray() : array
```

**Summary**

Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации
Является алиасом метода AbstractObject::jsonSerialize()

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

**Returns:** array - Ассоциативный массив со свойствами текущего объекта


<a name="method_getUnknownProperties" class="anchor"></a>
#### protected getUnknownProperties() : array

```php
protected getUnknownProperties() : array
```

**Summary**

Возвращает массив свойств которые не существуют, но были заданы у объекта

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

**Returns:** array - Ассоциативный массив с не существующими у текущего объекта свойствами



---

### Top Namespaces

* [\YooKassa](../namespaces/yookassa.md)

---

### Reports
* [Errors - 0](../reports/errors.md)
* [Markers - 1](../reports/markers.md)
* [Deprecated - 43](../reports/deprecated.md)

---

This document was automatically generated from source code comments on 2024-09-18 using [phpDocumentor](http://www.phpdoc.org/)

&copy; 2024 YooMoney