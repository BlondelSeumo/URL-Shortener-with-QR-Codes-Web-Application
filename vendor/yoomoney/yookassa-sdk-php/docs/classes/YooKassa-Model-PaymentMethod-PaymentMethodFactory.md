# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Model\PaymentMethod\PaymentMethodFactory
### Namespace: [\YooKassa\Model\PaymentMethod](../namespaces/yookassa-model-paymentmethod.md)
---
**Summary:**

Фабрика создания объекта платежных методов из массива


---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [YANDEX_MONEY](../classes/YooKassa-Model-PaymentMethod-PaymentMethodFactory.md#constant_YANDEX_MONEY) | *deprecated* |  |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [factory()](../classes/YooKassa-Model-PaymentMethod-PaymentMethodFactory.md#method_factory) |  | Фабричный метод создания объекта платежных данных по типу |
| public | [factoryFromArray()](../classes/YooKassa-Model-PaymentMethod-PaymentMethodFactory.md#method_factoryFromArray) |  | Фабричный метод создания объекта платежных данных из массива |

---
### Details
* File: [lib/Model/PaymentMethod/PaymentMethodFactory.php](../../lib/Model/PaymentMethod/PaymentMethodFactory.php)
* Package: YooKassa
* Class Hierarchy:
  * \YooKassa\Model\PaymentMethod\PaymentMethodFactory

---
## Constants
<a name="constant_YANDEX_MONEY" class="anchor"></a>
###### ~~YANDEX_MONEY~~
```php
YANDEX_MONEY = 'yandex_money'
```

**deprecated**
Для поддержки старых платежей


---
## Methods
<a name="method_factory" class="anchor"></a>
#### public factory() : \YooKassa\Model\PaymentMethod\AbstractPaymentMethod

```php
public factory(string $type) : \YooKassa\Model\PaymentMethod\AbstractPaymentMethod
```

**Summary**

Фабричный метод создания объекта платежных данных по типу

**Details:**
* Inherited From: [\YooKassa\Model\PaymentMethod\PaymentMethodFactory](../classes/YooKassa-Model-PaymentMethod-PaymentMethodFactory.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | type  | Тип платежного метода |

**Returns:** \YooKassa\Model\PaymentMethod\AbstractPaymentMethod - 


<a name="method_factoryFromArray" class="anchor"></a>
#### public factoryFromArray() : \YooKassa\Model\PaymentMethod\AbstractPaymentMethod

```php
public factoryFromArray(array $data, string|null $type = null) : \YooKassa\Model\PaymentMethod\AbstractPaymentMethod
```

**Summary**

Фабричный метод создания объекта платежных данных из массива

**Details:**
* Inherited From: [\YooKassa\Model\PaymentMethod\PaymentMethodFactory](../classes/YooKassa-Model-PaymentMethod-PaymentMethodFactory.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array</code> | data  | Массив платежных данных |
| <code lang="php">string OR null</code> | type  | Тип платежного метода |

**Returns:** \YooKassa\Model\PaymentMethod\AbstractPaymentMethod - 



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