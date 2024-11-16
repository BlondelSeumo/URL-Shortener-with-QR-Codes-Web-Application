# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder
### Namespace: [\YooKassa\Request\SelfEmployed](../namespaces/yookassa-request-selfemployed.md)
---
**Summary:**

Класс билдера объектов запросов к API на создание самозанятого


---
### Constants
* No constants found

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| protected | [$currentObject](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md#property_currentObject) |  | Собираемый объект запроса |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [__construct()](../classes/YooKassa-Common-AbstractRequestBuilder.md#method___construct) |  | Конструктор, инициализирует пустой запрос, который в будущем начнём собирать |
| public | [build()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md#method_build) |  | Строит и возвращает объект запроса для отправки в API ЮKassa |
| public | [setConfirmation()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md#method_setConfirmation) |  | Устанавливает сценарий подтверждения. |
| public | [setItn()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md#method_setItn) |  | Устанавливает ИНН самозанятого |
| public | [setOptions()](../classes/YooKassa-Common-AbstractRequestBuilder.md#method_setOptions) |  | Устанавливает свойства запроса из массива |
| public | [setPhone()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md#method_setPhone) |  | Устанавливает телефон самозанятого. |
| protected | [initCurrentObject()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md#method_initCurrentObject) |  | Инициализирует объект запроса, который в дальнейшем будет собираться билдером |

---
### Details
* File: [lib/Request/SelfEmployed/SelfEmployedRequestBuilder.php](../../lib/Request/SelfEmployed/SelfEmployedRequestBuilder.php)
* Package: YooKassa
* Class Hierarchy: 
  * [\YooKassa\Common\AbstractRequestBuilder](../classes/YooKassa-Common-AbstractRequestBuilder.md)
  * \YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| todo |  | @example 02-builder.php 11 78 Пример использования билдера |

---
## Properties
<a name="property_currentObject"></a>
#### protected $currentObject : \YooKassa\Request\SelfEmployed\SelfEmployedRequest
---
**Summary**

Собираемый объект запроса

**Type:** <a href="../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequest.html"><abbr title="\YooKassa\Request\SelfEmployed\SelfEmployedRequest">SelfEmployedRequest</abbr></a>

**Details:**



---
## Methods
<a name="method___construct" class="anchor"></a>
#### public __construct() : mixed

```php
public __construct() : mixed
```

**Summary**

Конструктор, инициализирует пустой запрос, который в будущем начнём собирать

**Details:**
* Inherited From: [\YooKassa\Common\AbstractRequestBuilder](../classes/YooKassa-Common-AbstractRequestBuilder.md)

**Returns:** mixed - 


<a name="method_build" class="anchor"></a>
#### public build() : \YooKassa\Request\SelfEmployed\SelfEmployedRequest|\YooKassa\Common\AbstractRequest

```php
public build(array|null $options = null) : \YooKassa\Request\SelfEmployed\SelfEmployedRequest|\YooKassa\Common\AbstractRequest
```

**Summary**

Строит и возвращает объект запроса для отправки в API ЮKassa

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR null</code> | options  | Массив параметров для установки в объект запроса |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidRequestException | Выбрасывается если собрать объект запроса не удалось |

**Returns:** \YooKassa\Request\SelfEmployed\SelfEmployedRequest|\YooKassa\Common\AbstractRequest - Инстанс объекта запроса


<a name="method_setConfirmation" class="anchor"></a>
#### public setConfirmation() : self

```php
public setConfirmation(\YooKassa\Model\SelfEmployed\SelfEmployedConfirmation|array|null $value) : self
```

**Summary**

Устанавливает сценарий подтверждения.

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\SelfEmployed\SelfEmployedConfirmation OR array OR null</code> | value  | Сценарий подтверждения. |

**Returns:** self - Инстанс билдера запросов


<a name="method_setItn" class="anchor"></a>
#### public setItn() : self

```php
public setItn(string|null $value) : self
```

**Summary**

Устанавливает ИНН самозанятого

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | value  | ИНН самозанятого |

**Returns:** self - Инстанс билдера запросов


<a name="method_setOptions" class="anchor"></a>
#### public setOptions() : \YooKassa\Common\AbstractRequestBuilder

```php
public setOptions(array|\Traversable $options) : \YooKassa\Common\AbstractRequestBuilder
```

**Summary**

Устанавливает свойства запроса из массива

**Details:**
* Inherited From: [\YooKassa\Common\AbstractRequestBuilder](../classes/YooKassa-Common-AbstractRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \Traversable</code> | options  | Массив свойств запроса |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \InvalidArgumentException | Выбрасывается если аргумент не массив и не итерируемый объект |
| \YooKassa\Common\Exceptions\InvalidPropertyException | Выбрасывается если не удалось установить один из параметров, переданныч в массиве настроек |

**Returns:** \YooKassa\Common\AbstractRequestBuilder - Инстанс текущего билдера запросов


<a name="method_setPhone" class="anchor"></a>
#### public setPhone() : self

```php
public setPhone(string|null $value) : self
```

**Summary**

Устанавливает телефон самозанятого.

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | value  | Телефон самозанятого. |

**Returns:** self - Инстанс билдера запросов


<a name="method_initCurrentObject" class="anchor"></a>
#### protected initCurrentObject() : \YooKassa\Request\SelfEmployed\SelfEmployedRequest

```php
protected initCurrentObject() : \YooKassa\Request\SelfEmployed\SelfEmployedRequest
```

**Summary**

Инициализирует объект запроса, который в дальнейшем будет собираться билдером

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestBuilder.md)

**Returns:** \YooKassa\Request\SelfEmployed\SelfEmployedRequest - Инстанс собираемого объекта запроса к API



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