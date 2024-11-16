# [YooKassa API SDK](../home.md)

# Interface: ReceiptItemInterface
### Namespace: [\YooKassa\Model](../namespaces/yookassa-model.md)
---
**Summary:**

Interface ReceiptItemInterface

---
### Constants
* No constants found

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [getAdditionalPaymentSubjectProps()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getAdditionalPaymentSubjectProps) |  | Возвращает дополнительный реквизит предмета расчета |
| public | [getAgentType()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getAgentType) |  | Возвращает тип посредника, реализующего товар или услугу |
| public | [getAmount()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getAmount) |  | Возвращает общую стоимость покупаемого товара в копейках/центах |
| public | [getCountryOfOriginCode()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getCountryOfOriginCode) |  | Возвращает код страны происхождения товара по общероссийскому классификатору стран мира |
| public | [getCustomsDeclarationNumber()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getCustomsDeclarationNumber) |  | Возвращает номер таможенной декларации |
| public | [getDescription()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getDescription) |  | Возвращает наименование товара |
| public | [getExcise()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getExcise) |  | Возвращает сумму акциза товара с учетом копеек |
| public | [getMarkCodeInfo()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getMarkCodeInfo) |  | Возвращает код товара |
| public | [getMarkMode()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getMarkMode) |  | Возвращает режим обработки кода маркировки |
| public | [getMarkQuantity()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getMarkQuantity) |  | Возвращает дробное количество маркированного товара |
| public | [getMeasure()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getMeasure) |  | Возвращает меру количества предмета расчета |
| public | [getPaymentMode()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getPaymentMode) |  | Возвращает признак способа расчета |
| public | [getPaymentSubject()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getPaymentSubject) |  | Возвращает признак предмета расчета |
| public | [getPaymentSubjectIndustryDetails()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getPaymentSubjectIndustryDetails) |  | Возвращает отраслевой реквизит чека |
| public | [getPrice()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getPrice) |  | Возвращает цену товара |
| public | [getProductCode()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getProductCode) |  | Возвращает код товара — уникальный номер, который присваивается экземпляру товара при маркировке |
| public | [getQuantity()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getQuantity) |  | Возвращает количество товара |
| public | [getSupplier()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getSupplier) |  | Возвращает информацию о поставщике товара или услуги |
| public | [getVatCode()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_getVatCode) |  | Возвращает ставку НДС |
| public | [isShipping()](../classes/YooKassa-Model-ReceiptItemInterface.md#method_isShipping) |  | Проверяет, является ли текущий элемент чека доставкой |

---
### Details
* File: [lib/Model/ReceiptItemInterface.php](../../lib/Model/ReceiptItemInterface.php)
* Package: \YooKassa

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| property-read |  | Наименование товара (тег в 54 ФЗ — 1030) |
| property-read |  | Количество (тег в 54 ФЗ — 1023) |
| property-read |  | Мера количества предмета расчета (тег в 54 ФЗ — 2108) |
| property-read |  | Дробное количество маркированного товара (тег в 54 ФЗ — 1291) |
| property-read |  | Дробное количество маркированного товара (тег в 54 ФЗ — 1291) |
| property-read |  | Суммарная стоимость покупаемого товара в копейках/центах |
| property-read |  | Цена товара (тег в 54 ФЗ — 1079) |
| property-read |  | Ставка НДС, число 1-6 (тег в 54 ФЗ — 1199) |
| property-read |  | Ставка НДС, число 1-6 (тег в 54 ФЗ — 1199) |
| property-read |  | Признак предмета расчета (тег в 54 ФЗ — 1212) |
| property-read |  | Признак предмета расчета (тег в 54 ФЗ — 1212) |
| property-read |  | Признак способа расчета (тег в 54 ФЗ — 1214) |
| property-read |  | Признак способа расчета (тег в 54 ФЗ — 1214) |
| property-read |  | Код страны происхождения товара (тег в 54 ФЗ — 1230) |
| property-read |  | Код страны происхождения товара (тег в 54 ФЗ — 1230) |
| property-read |  | Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231 |
| property-read |  | Номер таможенной декларации (от 1 до 32 символов). Тег в 54 ФЗ — 1231 |
| property-read |  | Сумма акциза товара с учетом копеек (тег в 54 ФЗ — 1229) |
| property-read |  | Код товара — уникальный номер, который присваивается экземпляру товара при маркировке (тег в 54 ФЗ — 1162) |
| property-read |  | Код товара — уникальный номер, который присваивается экземпляру товара при маркировке (тег в 54 ФЗ — 1162) |
| property-read |  | Код товара (тег в 54 ФЗ — 1163) |
| property-read |  | Код товара (тег в 54 ФЗ — 1163) |
| property-read |  | Режим обработки кода маркировки (тег в 54 ФЗ — 2102) |
| property-read |  | Режим обработки кода маркировки (тег в 54 ФЗ — 2102) |
| property-read |  | Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260) |
| property-read |  | Отраслевой реквизит предмета расчета (тег в 54 ФЗ — 1260) |
| property-read |  | Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191) |
| property-read |  | Дополнительный реквизит предмета расчета (тег в 54 ФЗ — 1191) |
| property-read |  | Информация о поставщике товара или услуги (тег в 54 ФЗ — 1224) |
| property-read |  | Тип посредника, реализующего товар или услугу |
| property-read |  | Тип посредника, реализующего товар или услугу |

---
## Methods
<a name="method_getDescription" class="anchor"></a>
#### public getDescription() : string

```php
public getDescription() : string
```

**Summary**

Возвращает наименование товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string - Наименование товара


<a name="method_getQuantity" class="anchor"></a>
#### public getQuantity() : float

```php
public getQuantity() : float
```

**Summary**

Возвращает количество товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** float - Количество купленного товара


<a name="method_getAmount" class="anchor"></a>
#### public getAmount() : float

```php
public getAmount() : float
```

**Summary**

Возвращает общую стоимость покупаемого товара в копейках/центах

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** float - Сумма стоимости покупаемого товара


<a name="method_getPrice" class="anchor"></a>
#### public getPrice() : \YooKassa\Model\AmountInterface

```php
public getPrice() : \YooKassa\Model\AmountInterface
```

**Summary**

Возвращает цену товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** \YooKassa\Model\AmountInterface - Цена товара


<a name="method_getVatCode" class="anchor"></a>
#### public getVatCode() : int|null

```php
public getVatCode() : int|null
```

**Summary**

Возвращает ставку НДС

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** int|null - Ставка НДС, число 1-6, или null, если ставка не задана


<a name="method_getPaymentSubject" class="anchor"></a>
#### public getPaymentSubject() : string|null

```php
public getPaymentSubject() : string|null
```

**Summary**

Возвращает признак предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string|null - Признак предмета расчета


<a name="method_getPaymentMode" class="anchor"></a>
#### public getPaymentMode() : string|null

```php
public getPaymentMode() : string|null
```

**Summary**

Возвращает признак способа расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string|null - Признак способа расчета


<a name="method_getProductCode" class="anchor"></a>
#### public getProductCode() : string|null

```php
public getProductCode() : string|null
```

**Summary**

Возвращает код товара — уникальный номер, который присваивается экземпляру товара при маркировке

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string|null - Код товара


<a name="method_getMarkCodeInfo" class="anchor"></a>
#### public getMarkCodeInfo() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public getMarkCodeInfo() : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Summary**

Возвращает код товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - Код товара


<a name="method_getMeasure" class="anchor"></a>
#### public getMeasure() : string

```php
public getMeasure() : string
```

**Summary**

Возвращает меру количества предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string - Мера количества предмета расчета


<a name="method_getMarkMode" class="anchor"></a>
#### public getMarkMode() : string

```php
public getMarkMode() : string
```

**Summary**

Возвращает режим обработки кода маркировки

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string - Режим обработки кода маркировки


<a name="method_getMarkQuantity" class="anchor"></a>
#### public getMarkQuantity() : \YooKassa\Model\Receipt\MarkQuantity

```php
public getMarkQuantity() : \YooKassa\Model\Receipt\MarkQuantity
```

**Summary**

Возвращает дробное количество маркированного товара

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** \YooKassa\Model\Receipt\MarkQuantity - Дробное количество маркированного товара


<a name="method_getPaymentSubjectIndustryDetails" class="anchor"></a>
#### public getPaymentSubjectIndustryDetails() : \YooKassa\Model\Receipt\IndustryDetails[]

```php
public getPaymentSubjectIndustryDetails() : \YooKassa\Model\Receipt\IndustryDetails[]
```

**Summary**

Возвращает отраслевой реквизит чека

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** \YooKassa\Model\Receipt\IndustryDetails[] - Отраслевой реквизит чека


<a name="method_getAdditionalPaymentSubjectProps" class="anchor"></a>
#### public getAdditionalPaymentSubjectProps() : string

```php
public getAdditionalPaymentSubjectProps() : string
```

**Summary**

Возвращает дополнительный реквизит предмета расчета

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string - Дополнительный реквизит предмета расчета


<a name="method_getCountryOfOriginCode" class="anchor"></a>
#### public getCountryOfOriginCode() : string|null

```php
public getCountryOfOriginCode() : string|null
```

**Summary**

Возвращает код страны происхождения товара по общероссийскому классификатору стран мира

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string|null - Код страны происхождения товара


<a name="method_getCustomsDeclarationNumber" class="anchor"></a>
#### public getCustomsDeclarationNumber() : string|null

```php
public getCustomsDeclarationNumber() : string|null
```

**Summary**

Возвращает номер таможенной декларации

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string|null - Номер таможенной декларации (от 1 до 32 символов)


<a name="method_getExcise" class="anchor"></a>
#### public getExcise() : float|null

```php
public getExcise() : float|null
```

**Summary**

Возвращает сумму акциза товара с учетом копеек

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** float|null - Сумма акциза товара с учетом копеек


<a name="method_getSupplier" class="anchor"></a>
#### public getSupplier() : \YooKassa\Model\SupplierInterface

```php
public getSupplier() : \YooKassa\Model\SupplierInterface
```

**Summary**

Возвращает информацию о поставщике товара или услуги

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** \YooKassa\Model\SupplierInterface - Информация о поставщике товара или услуги


<a name="method_getAgentType" class="anchor"></a>
#### public getAgentType() : string

```php
public getAgentType() : string
```

**Summary**

Возвращает тип посредника, реализующего товар или услугу

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** string - Тип посредника, реализующего товар или услугу.


<a name="method_isShipping" class="anchor"></a>
#### public isShipping() : bool

```php
public isShipping() : bool
```

**Summary**

Проверяет, является ли текущий элемент чека доставкой

**Details:**
* Inherited From: [\YooKassa\Model\ReceiptItemInterface](../classes/YooKassa-Model-ReceiptItemInterface.md)

**Returns:** bool - True если доставка, false если обычный товар




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