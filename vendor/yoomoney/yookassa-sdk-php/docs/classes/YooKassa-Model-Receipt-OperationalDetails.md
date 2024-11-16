# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Model\Receipt\OperationalDetails
### Namespace: [\YooKassa\Model\Receipt](../namespaces/yookassa-model-receipt.md)
---
**Summary:**

Class OperationalDetails

**Description:**

Данные операционного реквизита чека

---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [OPERATION_ID_MAX_LENGTH](../classes/YooKassa-Model-Receipt-OperationalDetails.md#constant_OPERATION_ID_MAX_LENGTH) |  |  |
| public | [VALUE_MAX_LENGTH](../classes/YooKassa-Model-Receipt-OperationalDetails.md#constant_VALUE_MAX_LENGTH) |  |  |

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [$created_at](../classes/YooKassa-Model-Receipt-OperationalDetails.md#property_created_at) |  | Время создания операции (тег в 54 ФЗ — 1273) |
| public | [$createdAt](../classes/YooKassa-Model-Receipt-OperationalDetails.md#property_createdAt) |  | Время создания операции (тег в 54 ФЗ — 1273) |
| public | [$operation_id](../classes/YooKassa-Model-Receipt-OperationalDetails.md#property_operation_id) |  | Идентификатор операции (тег в 54 ФЗ — 1271) |
| public | [$operationId](../classes/YooKassa-Model-Receipt-OperationalDetails.md#property_operationId) |  | Идентификатор операции (тег в 54 ФЗ — 1271) |
| public | [$value](../classes/YooKassa-Model-Receipt-OperationalDetails.md#property_value) |  | Данные операции (тег в 54 ФЗ — 1272) |

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
| public | [getCreatedAt()](../classes/YooKassa-Model-Receipt-OperationalDetails.md#method_getCreatedAt) |  | Возвращает время создания операции |
| public | [getOperationId()](../classes/YooKassa-Model-Receipt-OperationalDetails.md#method_getOperationId) |  | Возвращает идентификатор операции |
| public | [getValue()](../classes/YooKassa-Model-Receipt-OperationalDetails.md#method_getValue) |  | Возвращает данные операции |
| public | [jsonSerialize()](../classes/YooKassa-Model-Receipt-OperationalDetails.md#method_jsonSerialize) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации |
| public | [offsetExists()](../classes/YooKassa-Common-AbstractObject.md#method_offsetExists) |  | Проверяет наличие свойства |
| public | [offsetGet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetGet) |  | Возвращает значение свойства |
| public | [offsetSet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetSet) |  | Устанавливает значение свойства |
| public | [offsetUnset()](../classes/YooKassa-Common-AbstractObject.md#method_offsetUnset) |  | Удаляет свойство |
| public | [setCreatedAt()](../classes/YooKassa-Model-Receipt-OperationalDetails.md#method_setCreatedAt) |  | Устанавливает время создания операции |
| public | [setOperationId()](../classes/YooKassa-Model-Receipt-OperationalDetails.md#method_setOperationId) |  | Устанавливает идентификатор операции |
| public | [setValue()](../classes/YooKassa-Model-Receipt-OperationalDetails.md#method_setValue) |  | Устанавливает данные операции |
| public | [toArray()](../classes/YooKassa-Common-AbstractObject.md#method_toArray) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации Является алиасом метода AbstractObject::jsonSerialize() |
| protected | [getUnknownProperties()](../classes/YooKassa-Common-AbstractObject.md#method_getUnknownProperties) |  | Возвращает массив свойств которые не существуют, но были заданы у объекта |

---
### Details
* File: [lib/Model/Receipt/OperationalDetails.php](../../lib/Model/Receipt/OperationalDetails.php)
* Package: YooKassa
* Class Hierarchy: 
  * [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)
  * \YooKassa\Model\Receipt\OperationalDetails

---
## Constants
<a name="constant_OPERATION_ID_MAX_LENGTH" class="anchor"></a>
###### OPERATION_ID_MAX_LENGTH
```php
OPERATION_ID_MAX_LENGTH = 256 : int
```


<a name="constant_VALUE_MAX_LENGTH" class="anchor"></a>
###### VALUE_MAX_LENGTH
```php
VALUE_MAX_LENGTH = 64 : int
```



---
## Properties
<a name="property_created_at"></a>
#### public $created_at : \DateTime
---
***Description***

Время создания операции (тег в 54 ФЗ — 1273)

**Type:** \DateTime

**Details:**


<a name="property_createdAt"></a>
#### public $createdAt : \DateTime
---
***Description***

Время создания операции (тег в 54 ФЗ — 1273)

**Type:** \DateTime

**Details:**


<a name="property_operation_id"></a>
#### public $operation_id : string
---
***Description***

Идентификатор операции (тег в 54 ФЗ — 1271)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_operationId"></a>
#### public $operationId : string
---
***Description***

Идентификатор операции (тег в 54 ФЗ — 1271)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_value"></a>
#### public $value : string
---
***Description***

Данные операции (тег в 54 ФЗ — 1272)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

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


<a name="method_getCreatedAt" class="anchor"></a>
#### public getCreatedAt() : \DateTime

```php
public getCreatedAt() : \DateTime
```

**Summary**

Возвращает время создания операции

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\OperationalDetails](../classes/YooKassa-Model-Receipt-OperationalDetails.md)

**Returns:** \DateTime - Время создания операции


<a name="method_getOperationId" class="anchor"></a>
#### public getOperationId() : string

```php
public getOperationId() : string
```

**Summary**

Возвращает идентификатор операции

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\OperationalDetails](../classes/YooKassa-Model-Receipt-OperationalDetails.md)

**Returns:** string - Идентификатор операции


<a name="method_getValue" class="anchor"></a>
#### public getValue() : string

```php
public getValue() : string
```

**Summary**

Возвращает данные операции

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\OperationalDetails](../classes/YooKassa-Model-Receipt-OperationalDetails.md)

**Returns:** string - Данные операции


<a name="method_jsonSerialize" class="anchor"></a>
#### public jsonSerialize() : array

```php
public jsonSerialize() : array
```

**Summary**

Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\OperationalDetails](../classes/YooKassa-Model-Receipt-OperationalDetails.md)

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


<a name="method_setCreatedAt" class="anchor"></a>
#### public setCreatedAt() : mixed

```php
public setCreatedAt(string|\DateTime $value) : mixed
```

**Summary**

Устанавливает время создания операции

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\OperationalDetails](../classes/YooKassa-Model-Receipt-OperationalDetails.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR \DateTime</code> | value  | Время создания операции |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \Exception |  |

**Returns:** mixed - 


<a name="method_setOperationId" class="anchor"></a>
#### public setOperationId() : mixed

```php
public setOperationId(string $value) : mixed
```

**Summary**

Устанавливает идентификатор операции

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\OperationalDetails](../classes/YooKassa-Model-Receipt-OperationalDetails.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Идентификатор операции |

**Returns:** mixed - 


<a name="method_setValue" class="anchor"></a>
#### public setValue() : mixed

```php
public setValue(string $value) : mixed
```

**Summary**

Устанавливает данные операции

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\OperationalDetails](../classes/YooKassa-Model-Receipt-OperationalDetails.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Данные операции |

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