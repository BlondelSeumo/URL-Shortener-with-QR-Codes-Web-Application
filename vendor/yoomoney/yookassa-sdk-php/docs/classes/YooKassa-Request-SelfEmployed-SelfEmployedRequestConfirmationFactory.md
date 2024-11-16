# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationFactory
### Namespace: [\YooKassa\Request\SelfEmployed](../namespaces/yookassa-request-selfemployed.md)
---
**Summary:**

Class SelfEmployedRequestConfirmationFactory


---
### Constants
* No constants found

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [factory()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestConfirmationFactory.md#method_factory) |  | Возвращает объект, соответствующий типу подтверждения платежа |
| public | [factoryFromArray()](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestConfirmationFactory.md#method_factoryFromArray) |  | Возвращает объект, соответствующий типу подтверждения платежа, из массива данных |

---
### Details
* File: [lib/Request/SelfEmployed/SelfEmployedRequestConfirmationFactory.php](../../lib/Request/SelfEmployed/SelfEmployedRequestConfirmationFactory.php)
* Package: YooKassa
* Class Hierarchy:
  * \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationFactory

---
## Methods
<a name="method_factory" class="anchor"></a>
#### public factory() : \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation

```php
public factory(string $type) : \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation
```

**Summary**

Возвращает объект, соответствующий типу подтверждения платежа

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationFactory](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestConfirmationFactory.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | type  | Тип подтверждения платежа |

**Returns:** \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation - 


<a name="method_factoryFromArray" class="anchor"></a>
#### public factoryFromArray() : \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation

```php
public factoryFromArray(array $data, string|null $type = null) : \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation
```

**Summary**

Возвращает объект, соответствующий типу подтверждения платежа, из массива данных

**Details:**
* Inherited From: [\YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationFactory](../classes/YooKassa-Request-SelfEmployed-SelfEmployedRequestConfirmationFactory.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array</code> | data  | Массив данных подтверждения платежа |
| <code lang="php">string OR null</code> | type  | Тип подтверждения платежа |

**Returns:** \YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation - 



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