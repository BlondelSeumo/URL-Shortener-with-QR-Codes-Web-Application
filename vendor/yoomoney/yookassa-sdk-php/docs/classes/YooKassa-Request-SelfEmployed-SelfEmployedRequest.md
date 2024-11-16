# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Request\SelfEmployed\SelfEmployedRequest
### Namespace: [\YooKassa\Request\SelfEmployed](../namespaces/yookassa-request-selfemployed.md)
---
**Summary:**

Класс, представляющий модель SelfEmployedRequest.

**Description:**

Запрос на создание объекта самозанятого.

---
### Constants
* No constants found

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [$confirmation](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#property_confirmation) |  | Сценарий подтверждения пользователем заявки ЮMoney на получение прав для регистрации чеков в сервисе Мой налог. |
| public | [$itn](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#property_itn) |  | ИНН самозанятого. |
| public | [$phone](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#property_phone) |  | Телефон самозанятого, который привязан к личному кабинету в сервисе Мой налог. |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [__construct()](../classes/YooKassa-Common-AbstractObject.md#method___construct) |  | AbstractObject constructor. |
| public | [__get()](../classes/YooKassa-Common-AbstractObject.md#method___get) |  | Возвращает значение свойства |
| public | [__isset()](../classes/YooKassa-Common-AbstractObject.md#method___isset) |  | Проверяет наличие свойства |
| public | [__set()](../classes/YooKassa-Common-AbstractObject.md#method___set) |  | Устанавливает значение свойства |
| public | [__unset()](../classes/YooKassa-Common-AbstractObject.md#method___unset) |  | Удаляет свойство |
| public | [builder()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_builder) |  | Возвращает билдер объектов запросов создания платежа |
| public | [clearValidationError()](../classes/YooKassa-Common-AbstractRequest.md#method_clearValidationError) |  | Очищает статус валидации текущего запроса |
| public | [fromArray()](../classes/YooKassa-Common-AbstractObject.md#method_fromArray) |  | Устанавливает значения свойств текущего объекта из массива |
| public | [getConfirmation()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_getConfirmation) |  | Возвращает сценарий подтверждения. |
| public | [getItn()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_getItn) |  | Возвращает ИНН самозанятого |
| public | [getLastValidationError()](../classes/YooKassa-Common-AbstractRequest.md#method_getLastValidationError) |  | Возвращает последнюю ошибку валидации |
| public | [getPhone()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_getPhone) |  | Возвращает телефон самозанятого. |
| public | [hasConfirmation()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_hasConfirmation) |  | Проверяет наличие сценария подтверждения самозанятого в запросе |
| public | [hasItn()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_hasItn) |  | Проверяет наличие ИНН самозанятого в запросе |
| public | [hasPhone()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_hasPhone) |  | Проверяет наличие телефона самозанятого в запросе |
| public | [jsonSerialize()](../classes/YooKassa-Common-AbstractObject.md#method_jsonSerialize) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации |
| public | [offsetExists()](../classes/YooKassa-Common-AbstractObject.md#method_offsetExists) |  | Проверяет наличие свойства |
| public | [offsetGet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetGet) |  | Возвращает значение свойства |
| public | [offsetSet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetSet) |  | Устанавливает значение свойства |
| public | [offsetUnset()](../classes/YooKassa-Common-AbstractObject.md#method_offsetUnset) |  | Удаляет свойство |
| public | [setConfirmation()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_setConfirmation) |  | Устанавливает сценарий подтверждения. |
| public | [setItn()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_setItn) |  | Устанавливает ИНН самозанятого |
| public | [setPhone()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_setPhone) |  | Устанавливает телефон самозанятого. |
| public | [toArray()](../classes/YooKassa-Common-AbstractObject.md#method_toArray) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации Является алиасом метода AbstractObject::jsonSerialize() |
| public | [validate()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md#method_validate) |  | Проверяет на валидность текущий объект |
| protected | [getUnknownProperties()](../classes/YooKassa-Common-AbstractObject.md#method_getUnknownProperties) |  | Возвращает массив свойств которые не существуют, но были заданы у объекта |
| protected | [setValidationError()](../classes/YooKassa-Common-AbstractRequest.md#method_setValidationError) |  | Устанавливает ошибку валидации |

---
### Details
* File: [lib/Request/SelfEmployed/SelfEmployedRequest.php](../../lib/Request/SelfEmployed/SelfEmployedRequest.php)
* Package: YooKassa\Model
* Class Hierarchy:  
  * [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)
  * [\YooKassa\Common\AbstractRequest](../classes/YooKassa-Common-AbstractRequest.md)
  * \YooKassa\Request\SelfEmployed\SelfEmployedRequest

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| author |  | cms@yoomoney.ru |

---
## Properties
<a name="property_confirmation"></a>
#### public $confirmation : \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation|null
---
***Description***

Сценарий подтверждения пользователем заявки ЮMoney на получение прав для регистрации чеков в сервисе Мой налог.

**Type:** <a href="../\YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation|null"><abbr title="\YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation|null">SelfEmployedRequestConfirmation|null</abbr></a>

**Details:**


<a name="property_itn"></a>
#### public $itn : string|null
---
***Description***

ИНН самозанятого.

**Type:** <a href="../string|null"><abbr title="string|null">string|null</abbr></a>

**Details:**


<a name="property_phone"></a>
#### public $phone : string|null
---
***Description***

Телефон самозанятого, который привязан к личному кабинету в сервисе Мой налог.

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


<a name="method_builder" class="anchor"></a>
#### public builder() : \YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder

```php
Static public builder() : \YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder
```

**Summary**

Возвращает билдер объектов запросов создания платежа

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

**Returns:** \YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder - Инстанс билдера объектов запросов


<a name="method_clearValidationError" class="anchor"></a>
#### public clearValidationError() : mixed

```php
public clearValidationError() : mixed
```

**Summary**

Очищает статус валидации текущего запроса

**Details:**
* Inherited From: [\YooKassa\Common\AbstractRequest](../classes/YooKassa-Common-AbstractRequest.md)

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


<a name="method_getConfirmation" class="anchor"></a>
#### public getConfirmation() : \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation|null

```php
public getConfirmation() : \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation|null
```

**Summary**

Возвращает сценарий подтверждения.

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

**Returns:** \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation|null - Сценарий подтверждения


<a name="method_getItn" class="anchor"></a>
#### public getItn() : string|null

```php
public getItn() : string|null
```

**Summary**

Возвращает ИНН самозанятого

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

**Returns:** string|null - ИНН самозанятого


<a name="method_getLastValidationError" class="anchor"></a>
#### public getLastValidationError() : string

```php
public getLastValidationError() : string
```

**Summary**

Возвращает последнюю ошибку валидации

**Details:**
* Inherited From: [\YooKassa\Common\AbstractRequest](../classes/YooKassa-Common-AbstractRequest.md)

**Returns:** string - Последняя произошедшая ошибка валидации


<a name="method_getPhone" class="anchor"></a>
#### public getPhone() : string|null

```php
public getPhone() : string|null
```

**Summary**

Возвращает телефон самозанятого.

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

**Returns:** string|null - Телефон самозанятого


<a name="method_hasConfirmation" class="anchor"></a>
#### public hasConfirmation() : bool

```php
public hasConfirmation() : bool
```

**Summary**

Проверяет наличие сценария подтверждения самозанятого в запросе

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

**Returns:** bool - True если сценарий подтверждения самозанятого задан, false если нет


<a name="method_hasItn" class="anchor"></a>
#### public hasItn() : bool

```php
public hasItn() : bool
```

**Summary**

Проверяет наличие ИНН самозанятого в запросе

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

**Returns:** bool - True если ИНН самозанятого задан, false если нет


<a name="method_hasPhone" class="anchor"></a>
#### public hasPhone() : bool

```php
public hasPhone() : bool
```

**Summary**

Проверяет наличие телефона самозанятого в запросе

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

**Returns:** bool - True если телефон самозанятого задан, false если нет


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


<a name="method_setConfirmation" class="anchor"></a>
#### public setConfirmation() : $this

```php
public setConfirmation(\YooKassa\Model\SelfEmployed\SelfEmployedConfirmation|array|null $confirmation = null) : $this
```

**Summary**

Устанавливает сценарий подтверждения.

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\SelfEmployed\SelfEmployedConfirmation OR array OR null</code> | confirmation  | Сценарий подтверждения |

**Returns:** $this - 


<a name="method_setItn" class="anchor"></a>
#### public setItn() : $this

```php
public setItn(string|null $itn = null) : $this
```

**Summary**

Устанавливает ИНН самозанятого

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | itn  | ИНН самозанятого |

**Returns:** $this - 


<a name="method_setPhone" class="anchor"></a>
#### public setPhone() : $this

```php
public setPhone(string|null $phone = null) : $this
```

**Summary**

Устанавливает телефон самозанятого.

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | phone  | Телефон самозанятого |

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


<a name="method_validate" class="anchor"></a>
#### public validate() : bool

```php
public validate() : bool
```

**Summary**

Проверяет на валидность текущий объект

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequest](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.md)

**Returns:** bool - True если объект запроса валиден, false если нет


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


<a name="method_setValidationError" class="anchor"></a>
#### protected setValidationError() : mixed

```php
protected setValidationError(string $value) : mixed
```

**Summary**

Устанавливает ошибку валидации

**Details:**
* Inherited From: [\YooKassa\Common\AbstractRequest](../classes/YooKassa-Common-AbstractRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Ошибка, произошедшая при валидации объекта |

**Returns:** mixed - 



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