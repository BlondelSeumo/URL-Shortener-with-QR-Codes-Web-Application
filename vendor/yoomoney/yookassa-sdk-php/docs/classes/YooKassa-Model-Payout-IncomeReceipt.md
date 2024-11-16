# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Model\Payout\IncomeReceipt
### Namespace: [\YooKassa\Model\Payout](../namespaces/yookassa-model-payout.md)
---
**Summary:**

Класс, представляющий модель IncomeReceipt.

**Description:**

Данные чека, зарегистрированного в ФНС. Присутствует, если вы делаете выплату [самозанятому](/developers/payouts/scenario-extensions/self-employed).

---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [MAX_LENGTH_SERVICE_NAME](../classes/YooKassa-Model-Payout-IncomeReceipt.md#constant_MAX_LENGTH_SERVICE_NAME) |  |  |

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [$amount](../classes/YooKassa-Model-Payout-IncomeReceipt.md#property_amount) |  | Сумма, указанная в чеке. Присутствует, если в запросе передавалась сумма для печати в чеке. |
| public | [$npd_receipt_id](../classes/YooKassa-Model-Payout-IncomeReceipt.md#property_npd_receipt_id) |  | Идентификатор чека в сервисе. |
| public | [$npdReceiptId](../classes/YooKassa-Model-Payout-IncomeReceipt.md#property_npdReceiptId) |  | Идентификатор чека в сервисе. |
| public | [$service_name](../classes/YooKassa-Model-Payout-IncomeReceipt.md#property_service_name) |  | Описание услуги, оказанной получателем выплаты. Не более 50 символов. |
| public | [$serviceName](../classes/YooKassa-Model-Payout-IncomeReceipt.md#property_serviceName) |  | Описание услуги, оказанной получателем выплаты. Не более 50 символов. |
| public | [$url](../classes/YooKassa-Model-Payout-IncomeReceipt.md#property_url) |  | Ссылка на зарегистрированный чек. |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [__construct()](../classes/YooKassa-Common-AbstractObject.md#method___construct) |  | AbstractObject constructor. |
| public | [__get()](../classes/YooKassa-Common-AbstractObject.md#method___get) |  | Возвращает значение свойства |
| public | [__isset()](../classes/YooKassa-Common-AbstractObject.md#method___isset) |  | Проверяет наличие свойства |
| public | [__set()](../classes/YooKassa-Common-AbstractObject.md#method___set) |  | Устанавливает значение свойства |
| public | [__unset()](../classes/YooKassa-Common-AbstractObject.md#method___unset) |  | Удаляет свойство |
| public | [fromArray()](../classes/YooKassa-Common-AbstractObject.md#method_fromArray) |  | Устанавливает значения свойств текущего объекта из массива |
| public | [getAmount()](../classes/YooKassa-Model-Payout-IncomeReceipt.md#method_getAmount) |  | Возвращает amount. |
| public | [getNpdReceiptId()](../classes/YooKassa-Model-Payout-IncomeReceipt.md#method_getNpdReceiptId) |  | Возвращает npd_receipt_id. |
| public | [getServiceName()](../classes/YooKassa-Model-Payout-IncomeReceipt.md#method_getServiceName) |  | Возвращает service_name. |
| public | [getUrl()](../classes/YooKassa-Model-Payout-IncomeReceipt.md#method_getUrl) |  | Возвращает Ссылка на зарегистрированный чек. |
| public | [jsonSerialize()](../classes/YooKassa-Common-AbstractObject.md#method_jsonSerialize) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации |
| public | [offsetExists()](../classes/YooKassa-Common-AbstractObject.md#method_offsetExists) |  | Проверяет наличие свойства |
| public | [offsetGet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetGet) |  | Возвращает значение свойства |
| public | [offsetSet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetSet) |  | Устанавливает значение свойства |
| public | [offsetUnset()](../classes/YooKassa-Common-AbstractObject.md#method_offsetUnset) |  | Удаляет свойство |
| public | [setAmount()](../classes/YooKassa-Model-Payout-IncomeReceipt.md#method_setAmount) |  | Устанавливает amount. |
| public | [setNpdReceiptId()](../classes/YooKassa-Model-Payout-IncomeReceipt.md#method_setNpdReceiptId) |  | Устанавливает npd_receipt_id. |
| public | [setServiceName()](../classes/YooKassa-Model-Payout-IncomeReceipt.md#method_setServiceName) |  | Устанавливает service_name. |
| public | [setUrl()](../classes/YooKassa-Model-Payout-IncomeReceipt.md#method_setUrl) |  | Устанавливает ссылка на зарегистрированный чек. |
| public | [toArray()](../classes/YooKassa-Common-AbstractObject.md#method_toArray) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации Является алиасом метода AbstractObject::jsonSerialize() |
| protected | [getUnknownProperties()](../classes/YooKassa-Common-AbstractObject.md#method_getUnknownProperties) |  | Возвращает массив свойств которые не существуют, но были заданы у объекта |

---
### Details
* File: [lib/Model/Payout/IncomeReceipt.php](../../lib/Model/Payout/IncomeReceipt.php)
* Package: YooKassa\Model
* Class Hierarchy: 
  * [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)
  * \YooKassa\Model\Payout\IncomeReceipt

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| author |  | cms@yoomoney.ru |

---
## Constants
<a name="constant_MAX_LENGTH_SERVICE_NAME" class="anchor"></a>
###### MAX_LENGTH_SERVICE_NAME
```php
MAX_LENGTH_SERVICE_NAME = 50 : int
```



---
## Properties
<a name="property_amount"></a>
#### public $amount : \YooKassa\Model\AmountInterface|null
---
***Description***

Сумма, указанная в чеке. Присутствует, если в запросе передавалась сумма для печати в чеке.

**Type:** <a href="../\YooKassa\Model\AmountInterface|null"><abbr title="\YooKassa\Model\AmountInterface|null">AmountInterface|null</abbr></a>

**Details:**


<a name="property_npd_receipt_id"></a>
#### public $npd_receipt_id : string|null
---
***Description***

Идентификатор чека в сервисе.

**Type:** <a href="../string|null"><abbr title="string|null">string|null</abbr></a>

**Details:**


<a name="property_npdReceiptId"></a>
#### public $npdReceiptId : string|null
---
***Description***

Идентификатор чека в сервисе.

**Type:** <a href="../string|null"><abbr title="string|null">string|null</abbr></a>

**Details:**


<a name="property_service_name"></a>
#### public $service_name : string
---
***Description***

Описание услуги, оказанной получателем выплаты. Не более 50 символов.

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_serviceName"></a>
#### public $serviceName : string
---
***Description***

Описание услуги, оказанной получателем выплаты. Не более 50 символов.

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_url"></a>
#### public $url : string|null
---
***Description***

Ссылка на зарегистрированный чек.

**Type:** <a href="../string|null"><abbr title="string|null">string|null</abbr></a>

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


<a name="method_fromArray" class="anchor"></a>
#### public fromArray() : mixed

```php
public fromArray(array|\Traversable $sourceArray) : mixed
```

**Summary**

Устанавливает значения свойств текущего объекта из массива

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \Traversable</code> | sourceArray  | Ассоциативный массив с настройками |

**Returns:** mixed - 


<a name="method_getAmount" class="anchor"></a>
#### public getAmount() : \YooKassa\Model\AmountInterface|null

```php
public getAmount() : \YooKassa\Model\AmountInterface|null
```

**Summary**

Возвращает amount.

**Details:**
* Inherited From: [\YooKassa\Model\Payout\IncomeReceipt](../classes/YooKassa-Model-Payout-IncomeReceipt.md)

**Returns:** \YooKassa\Model\AmountInterface|null - 


<a name="method_getNpdReceiptId" class="anchor"></a>
#### public getNpdReceiptId() : string|null

```php
public getNpdReceiptId() : string|null
```

**Summary**

Возвращает npd_receipt_id.

**Details:**
* Inherited From: [\YooKassa\Model\Payout\IncomeReceipt](../classes/YooKassa-Model-Payout-IncomeReceipt.md)

**Returns:** string|null - 


<a name="method_getServiceName" class="anchor"></a>
#### public getServiceName() : string

```php
public getServiceName() : string
```

**Summary**

Возвращает service_name.

**Details:**
* Inherited From: [\YooKassa\Model\Payout\IncomeReceipt](../classes/YooKassa-Model-Payout-IncomeReceipt.md)

**Returns:** string - 


<a name="method_getUrl" class="anchor"></a>
#### public getUrl() : string|null

```php
public getUrl() : string|null
```

**Summary**

Возвращает Ссылка на зарегистрированный чек.

**Details:**
* Inherited From: [\YooKassa\Model\Payout\IncomeReceipt](../classes/YooKassa-Model-Payout-IncomeReceipt.md)

**Returns:** string|null - 


<a name="method_jsonSerialize" class="anchor"></a>
#### public jsonSerialize() : array

```php
public jsonSerialize() : array
```

**Summary**

Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

**Returns:** array - Ассоциативный массив со свойствами текущего объекта


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


<a name="method_setAmount" class="anchor"></a>
#### public setAmount() : $this

```php
public setAmount(\YooKassa\Model\AmountInterface|array|null $value = null) : $this
```

**Summary**

Устанавливает amount.

**Details:**
* Inherited From: [\YooKassa\Model\Payout\IncomeReceipt](../classes/YooKassa-Model-Payout-IncomeReceipt.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\AmountInterface OR array OR null</code> | value  |  |

**Returns:** $this - 


<a name="method_setNpdReceiptId" class="anchor"></a>
#### public setNpdReceiptId() : $this

```php
public setNpdReceiptId(string|null $value = null) : $this
```

**Summary**

Устанавливает npd_receipt_id.

**Details:**
* Inherited From: [\YooKassa\Model\Payout\IncomeReceipt](../classes/YooKassa-Model-Payout-IncomeReceipt.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | value  | Идентификатор чека в сервисе.  Пример: ~`208jd98zqe` |

**Returns:** $this - 


<a name="method_setServiceName" class="anchor"></a>
#### public setServiceName() : $this

```php
public setServiceName(string $value) : $this
```

**Summary**

Устанавливает service_name.

**Details:**
* Inherited From: [\YooKassa\Model\Payout\IncomeReceipt](../classes/YooKassa-Model-Payout-IncomeReceipt.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Описание услуги, оказанной получателем выплаты. Не более 50 символов. |

**Returns:** $this - 


<a name="method_setUrl" class="anchor"></a>
#### public setUrl() : $this

```php
public setUrl(string|null $value = null) : $this
```

**Summary**

Устанавливает ссылка на зарегистрированный чек.

**Details:**
* Inherited From: [\YooKassa\Model\Payout\IncomeReceipt](../classes/YooKassa-Model-Payout-IncomeReceipt.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | value  | Ссылка на зарегистрированный чек  Пример: ~`https://www.nalog.gov.ru/api/v1/receipt/<Идентификатор чека>/print` |

**Returns:** $this - 


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