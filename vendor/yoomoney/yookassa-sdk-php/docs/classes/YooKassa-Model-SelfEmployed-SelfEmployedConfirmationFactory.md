# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Model\SelfEmployed\SelfEmployedConfirmationFactory
### Namespace: [\YooKassa\Model\SelfEmployed](../namespaces/yookassa-model-selfemployed.md)
---
**Summary:**

Class ConfirmationFactory


---
### Constants
* No constants found

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [factory()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedConfirmationFactory.md#method_factory) |  | Возвращает объект, соответствующий типу подтверждения платежа |
| public | [factoryFromArray()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedConfirmationFactory.md#method_factoryFromArray) |  | Возвращает объект, соответствующий типу подтверждения платежа, из массива данных |

---
### Details
* File: [lib/Model/SelfEmployed/SelfEmployedConfirmationFactory.php](../../lib/Model/SelfEmployed/SelfEmployedConfirmationFactory.php)
* Package: YooKassa
* Class Hierarchy:
  * \YooKassa\Model\SelfEmployed\SelfEmployedConfirmationFactory

---
## Methods
<a name="method_factory" class="anchor"></a>
#### public factory() : \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation

```php
public factory(string $type) : \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation
```

**Summary**

Возвращает объект, соответствующий типу подтверждения платежа

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedConfirmationFactory](../classes/YooKassa-Model-SelfEmployed-SelfEmployedConfirmationFactory.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | type  | Тип подтверждения платежа |

**Returns:** \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation - 


<a name="method_factoryFromArray" class="anchor"></a>
#### public factoryFromArray() : \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation

```php
public factoryFromArray(array $data, string|null $type = null) : \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation
```

**Summary**

Возвращает объект, соответствующий типу подтверждения платежа, из массива данных

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedConfirmationFactory](../classes/YooKassa-Model-SelfEmployed-SelfEmployedConfirmationFactory.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array</code> | data  | Массив данных подтверждения платежа |
| <code lang="php">string OR null</code> | type  | Тип подтверждения платежа |

**Returns:** \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation - 



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