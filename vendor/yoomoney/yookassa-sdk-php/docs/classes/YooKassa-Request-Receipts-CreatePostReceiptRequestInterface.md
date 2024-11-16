# [YooKassa API SDK](../home.md)

# Interface: CreatePostReceiptRequestInterface
### Namespace: [\YooKassa\Request\Receipts](../namespaces/yookassa-request-receipts.md)
---
**Summary:**

Interface CreateReceiptRequestInterface

---
### Constants
* No constants found

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [addItem()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_addItem) |  | Добавляет товар в чек |
| public | [getAdditionalUserProps()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getAdditionalUserProps) |  | Возвращает дополнительный реквизит пользователя |
| public | [getCustomer()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getCustomer) |  | Возвращает информацию о плательщике. |
| public | [getItems()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getItems) |  | Возвращает список товаров в заказе |
| public | [getObjectId()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getObjectId) |  | Возвращает идентификатор объекта, для которого формируется чек |
| public | [getObjectType()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getObjectType) |  | Возвращает тип объекта чека |
| public | [getOnBehalfOf()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getOnBehalfOf) |  | Возвращает идентификатор магазина, от имени которого нужно отправить чек |
| public | [getReceiptIndustryDetails()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getReceiptIndustryDetails) |  | Возвращает отраслевой реквизит чека |
| public | [getReceiptOperationalDetails()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getReceiptOperationalDetails) |  | Возвращает операционный реквизит чека |
| public | [getSend()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getSend) |  | Возвращает признак отложенной отправки чека |
| public | [getSettlements()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getSettlements) |  | Возвращает Массив оплат, обеспечивающих выдачу товара |
| public | [getTaxSystemCode()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getTaxSystemCode) |  | Возвращает код системы налогообложения |
| public | [getType()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_getType) |  | Возвращает тип чека в онлайн-кассе |
| public | [notEmpty()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_notEmpty) |  | Проверяет есть ли в чеке хотя бы одна позиция |
| public | [setAdditionalUserProps()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setAdditionalUserProps) |  | Устанавливает дополнительный реквизит пользователя |
| public | [setCustomer()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setCustomer) |  | Устанавливает информацию о пользователе |
| public | [setItems()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setItems) |  | Устанавливает список товаров чека |
| public | [setObjectId()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setObjectId) |  | Устанавливает идентификатор объекта, для которого формируется чек |
| public | [setObjectType()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setObjectType) |  | Устанавливает тип объекта чека |
| public | [setOnBehalfOf()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setOnBehalfOf) |  | Устанавливает идентификатор магазина, от имени которого нужно отправить чек. |
| public | [setReceiptIndustryDetails()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setReceiptIndustryDetails) |  | Устанавливает отраслевой реквизит чека |
| public | [setReceiptOperationalDetails()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setReceiptOperationalDetails) |  | Устанавливает операционный реквизит чека |
| public | [setSend()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setSend) |  | Устанавливает признак отложенной отправки чека |
| public | [setSettlements()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setSettlements) |  | Устанавливает массив оплат, обеспечивающих выдачу товара |
| public | [setTaxSystemCode()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setTaxSystemCode) |  | Устанавливает код системы налогообложения |
| public | [setType()](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md#method_setType) |  | Устанавливает тип чека в онлайн-кассе |

---
### Details
* File: [lib/Request/Receipts/CreatePostReceiptRequestInterface.php](../../lib/Request/Receipts/CreatePostReceiptRequestInterface.php)
* Package: \YooKassa

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| property |  | Идентификатор объекта ("payment" или "refund), для которого формируется чек |
| property |  | Идентификатор объекта ("payment" или "refund), для которого формируется чек |
| property |  | Тип чека в онлайн-кассе: приход "payment" или возврат "refund" |
| property |  | Признак отложенной отправки чека |
| property |  | Информация о плательщике |
| property |  | Код системы налогообложения. Число 1-6 |
| property |  | Код системы налогообложения. Число 1-6 |
| property |  | Дополнительный реквизит пользователя |
| property |  | Дополнительный реквизит пользователя |
| property |  | Отраслевой реквизит чека |
| property |  | Отраслевой реквизит чека |
| property |  | Операционный реквизит чека |
| property |  | Операционный реквизит чека |
| property |  | Список товаров в заказе |
| property |  | Массив оплат, обеспечивающих выдачу товара |

---
## Methods
<a name="method_getObjectId" class="anchor"></a>
#### public getObjectId() : string

```php
public getObjectId() : string
```

**Summary**

Возвращает идентификатор объекта, для которого формируется чек

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** string - Идентификатор объекта


<a name="method_setObjectId" class="anchor"></a>
#### public setObjectId() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setObjectId(string $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает идентификатор объекта, для которого формируется чек

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Идентификатор объекта |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getType" class="anchor"></a>
#### public getType() : string

```php
public getType() : string
```

**Summary**

Возвращает тип чека в онлайн-кассе

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** string - Тип чека в онлайн-кассе: приход "payment" или возврат "refund"


<a name="method_setType" class="anchor"></a>
#### public setType() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setType(string $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает тип чека в онлайн-кассе

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Тип чека в онлайн-кассе: приход "payment" или возврат "refund" |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getObjectType" class="anchor"></a>
#### public getObjectType() : string

```php
public getObjectType() : string
```

**Summary**

Возвращает тип объекта чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** string - Тип объекта чека


<a name="method_setObjectType" class="anchor"></a>
#### public setObjectType() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setObjectType(string $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает тип объекта чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Тип объекта чека |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getSend" class="anchor"></a>
#### public getSend() : mixed

```php
public getSend() : mixed
```

**Summary**

Возвращает признак отложенной отправки чека

**Description**

 @return bool Признак отложенной отправки чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** mixed - 


<a name="method_setSend" class="anchor"></a>
#### public setSend() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setSend(bool $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает признак отложенной отправки чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">bool</code> | value  | Признак отложенной отправки чека |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getTaxSystemCode" class="anchor"></a>
#### public getTaxSystemCode() : mixed

```php
public getTaxSystemCode() : mixed
```

**Summary**

Возвращает код системы налогообложения

**Description**

 @return int Код системы налогообложения. Число 1-6

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** mixed - 


<a name="method_setTaxSystemCode" class="anchor"></a>
#### public setTaxSystemCode() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setTaxSystemCode(int $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает код системы налогообложения

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">int</code> | value  | Код системы налогообложения. Число 1-6 |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getAdditionalUserProps" class="anchor"></a>
#### public getAdditionalUserProps() : mixed

```php
public getAdditionalUserProps() : mixed
```

**Summary**

Возвращает дополнительный реквизит пользователя

**Description**

 @return AdditionalUserProps Дополнительный реквизит пользователя

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** mixed - 


<a name="method_setAdditionalUserProps" class="anchor"></a>
#### public setAdditionalUserProps() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setAdditionalUserProps(\YooKassa\Model\Receipt\AdditionalUserProps $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает дополнительный реквизит пользователя

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\Receipt\AdditionalUserProps</code> | value  | Дополнительный реквизит пользователя |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getReceiptIndustryDetails" class="anchor"></a>
#### public getReceiptIndustryDetails() : \YooKassa\Model\Receipt\IndustryDetails[]

```php
public getReceiptIndustryDetails() : \YooKassa\Model\Receipt\IndustryDetails[]
```

**Summary**

Возвращает отраслевой реквизит чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** \YooKassa\Model\Receipt\IndustryDetails[] - Отраслевой реквизит чека


<a name="method_setReceiptIndustryDetails" class="anchor"></a>
#### public setReceiptIndustryDetails() : mixed

```php
public setReceiptIndustryDetails(array|\YooKassa\Model\Receipt\IndustryDetails[] $value) : mixed
```

**Summary**

Устанавливает отраслевой реквизит чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \YooKassa\Model\Receipt\IndustryDetails[]</code> | value  | Отраслевой реквизит чека |

**Returns:** mixed - 


<a name="method_getReceiptOperationalDetails" class="anchor"></a>
#### public getReceiptOperationalDetails() : \YooKassa\Model\Receipt\OperationalDetails

```php
public getReceiptOperationalDetails() : \YooKassa\Model\Receipt\OperationalDetails
```

**Summary**

Возвращает операционный реквизит чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** \YooKassa\Model\Receipt\OperationalDetails - Операционный реквизит чека


<a name="method_setReceiptOperationalDetails" class="anchor"></a>
#### public setReceiptOperationalDetails() : mixed

```php
public setReceiptOperationalDetails(array|\YooKassa\Model\Receipt\OperationalDetails $value) : mixed
```

**Summary**

Устанавливает операционный реквизит чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \YooKassa\Model\Receipt\OperationalDetails</code> | value  | Операционный реквизит чека |

**Returns:** mixed - 


<a name="method_getCustomer" class="anchor"></a>
#### public getCustomer() : \YooKassa\Model\ReceiptCustomerInterface

```php
public getCustomer() : \YooKassa\Model\ReceiptCustomerInterface
```

**Summary**

Возвращает информацию о плательщике.

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** \YooKassa\Model\ReceiptCustomerInterface - Информация о плательщике


<a name="method_setCustomer" class="anchor"></a>
#### public setCustomer() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setCustomer(\YooKassa\Model\ReceiptCustomerInterface $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает информацию о пользователе

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\ReceiptCustomerInterface</code> | value  | Информация о плательщике |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getItems" class="anchor"></a>
#### public getItems() : mixed

```php
public getItems() : mixed
```

**Summary**

Возвращает список товаров в заказе

**Description**

 @return ReceiptItemInterface[]

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** mixed - 


<a name="method_setItems" class="anchor"></a>
#### public setItems() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setItems(\YooKassa\Model\ReceiptItemInterface[]|array $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает список товаров чека

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\ReceiptItemInterface[] OR array</code> | value  | Список товаров чека |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_addItem" class="anchor"></a>
#### public addItem() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public addItem(\YooKassa\Model\ReceiptItemInterface|array $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Добавляет товар в чек

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\ReceiptItemInterface OR array</code> | value  | Информация о товаре |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getSettlements" class="anchor"></a>
#### public getSettlements() : mixed

```php
public getSettlements() : mixed
```

**Summary**

Возвращает Массив оплат, обеспечивающих выдачу товара

**Description**

 @return SettlementInterface[]

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** mixed - 


<a name="method_setSettlements" class="anchor"></a>
#### public setSettlements() : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface

```php
public setSettlements(\YooKassa\Model\SettlementInterface[]|array $value) : \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface
```

**Summary**

Устанавливает массив оплат, обеспечивающих выдачу товара

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\SettlementInterface[] OR array</code> | value  | Массив оплат, обеспечивающих выдачу товара |

**Returns:** \YooKassa\Request\Receipts\CreatePostReceiptRequestInterface - 


<a name="method_getOnBehalfOf" class="anchor"></a>
#### public getOnBehalfOf() : string|null

```php
public getOnBehalfOf() : string|null
```

**Summary**

Возвращает идентификатор магазина, от имени которого нужно отправить чек

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** string|null - Идентификатор магазина, от имени которого нужно отправить чек


<a name="method_setOnBehalfOf" class="anchor"></a>
#### public setOnBehalfOf() : mixed

```php
public setOnBehalfOf(string $value) : mixed
```

**Summary**

Устанавливает идентификатор магазина, от имени которого нужно отправить чек.

**Description**

Выдается ЮKassa, отображается в разделе Продавцы личного кабинета (столбец shopId).
Необходимо передавать, если вы используете решение ЮKassa для платформ.

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Идентификатор магазина, от имени которого нужно отправить чек |

**Returns:** mixed - 


<a name="method_notEmpty" class="anchor"></a>
#### public notEmpty() : bool

```php
public notEmpty() : bool
```

**Summary**

Проверяет есть ли в чеке хотя бы одна позиция

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\CreatePostReceiptRequestInterface](../classes/YooKassa-Request-Receipts-CreatePostReceiptRequestInterface.md)

**Returns:** bool - True если чек не пуст, false если в чеке нет ни одной позиции




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