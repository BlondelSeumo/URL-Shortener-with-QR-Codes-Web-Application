# [YooKassa API SDK](../home.md)

# Interface: SelfEmployedInterface
### Namespace: [\YooKassa\Model\SelfEmployed](../namespaces/yookassa-model-selfemployed.md)
---
**Summary:**

Класс, представляющий модель SelfEmployed.

**Description:**

Объект самозанятого.

---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
* [public MIN_LENGTH_ID](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#constant_MIN_LENGTH_ID)
* [public MAX_LENGTH_ID](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#constant_MAX_LENGTH_ID)

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [getConfirmation()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_getConfirmation) |  | Возвращает сценарий подтверждения. |
| public | [getCreatedAt()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_getCreatedAt) |  | Возвращает время создания объекта самозанятого |
| public | [getId()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_getId) |  | Возвращает идентификатор самозанятого. |
| public | [getItn()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_getItn) |  | Возвращает ИНН самозанятого |
| public | [getPhone()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_getPhone) |  | Возвращает телефон самозанятого. |
| public | [getStatus()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_getStatus) |  | Возвращает статус самозанятого. |
| public | [getTest()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_getTest) |  | Возвращает признак тестовой операции. |
| public | [setConfirmation()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_setConfirmation) |  | Устанавливает сценарий подтверждения. |
| public | [setCreatedAt()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_setCreatedAt) |  | Устанавливает время создания объекта самозанятого |
| public | [setId()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_setId) |  | Устанавливает идентификатор самозанятого. |
| public | [setItn()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_setItn) |  | Устанавливает ИНН самозанятого |
| public | [setPhone()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_setPhone) |  | Устанавливает телефон самозанятого. |
| public | [setStatus()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_setStatus) |  | Устанавливает статус самозанятого. |
| public | [setTest()](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md#method_setTest) |  | Устанавливает признак тестовой операции. |

---
### Details
* File: [lib/Model/SelfEmployed/SelfEmployedInterface.php](../../lib/Model/SelfEmployed/SelfEmployedInterface.php)
* Package: \YooKassa\Model

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| author |  | cms@yoomoney.ru |
| property |  | Идентификатор самозанятого в ЮKassa. |
| property |  | Статус подключения самозанятого и выдачи ЮMoney прав на регистрацию чеков. |
| property |  | Время создания объекта самозанятого. |
| property |  | Время создания объекта самозанятого. |
| property |  | ИНН самозанятого. |
| property |  | Телефон самозанятого, который привязан к личному кабинету в сервисе Мой налог. |
| property |  | Сценарий подтверждения пользователем заявки ЮMoney на получение прав для регистрации чеков в сервисе Мой налог. |
| property |  | Идентификатор самозанятого в ЮKassa. |

---
## Constants
<a name="constant_MIN_LENGTH_ID" class="anchor"></a>
###### MIN_LENGTH_ID
```php
MIN_LENGTH_ID = 36 : int
```


<a name="constant_MAX_LENGTH_ID" class="anchor"></a>
###### MAX_LENGTH_ID
```php
MAX_LENGTH_ID = 50 : int
```



---
## Methods
<a name="method_getId" class="anchor"></a>
#### public getId() : string

```php
public getId() : string
```

**Summary**

Возвращает идентификатор самозанятого.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

**Returns:** string - 


<a name="method_setId" class="anchor"></a>
#### public setId() : $this

```php
public setId(string $id) : $this
```

**Summary**

Устанавливает идентификатор самозанятого.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | id  | Идентификатор самозанятого в ЮKassa |

**Returns:** $this - 


<a name="method_getStatus" class="anchor"></a>
#### public getStatus() : string

```php
public getStatus() : string
```

**Summary**

Возвращает статус самозанятого.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

**Returns:** string - Статус самозанятого


<a name="method_setStatus" class="anchor"></a>
#### public setStatus() : $this

```php
public setStatus(string $status) : $this
```

**Summary**

Устанавливает статус самозанятого.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | status  | Статус самозанятого |

**Returns:** $this - 


<a name="method_getCreatedAt" class="anchor"></a>
#### public getCreatedAt() : \DateTime

```php
public getCreatedAt() : \DateTime
```

**Summary**

Возвращает время создания объекта самозанятого

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

**Returns:** \DateTime - Время создания объекта самозанятого


<a name="method_setCreatedAt" class="anchor"></a>
#### public setCreatedAt() : $this

```php
public setCreatedAt(\DateTime|string|int $created_at) : $this
```

**Summary**

Устанавливает время создания объекта самозанятого

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\DateTime OR string OR int</code> | created_at  | Время создания объекта самозанятого |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\EmptyPropertyValueException | Выбрасывается если в метод была передана пустая дата |
| \YooKassa\Common\Exceptions\InvalidPropertyValueException | Выбрасывается если передали строку, которую не удалось привести к дате |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException|\Exception | Выбрасывается если был передан аргумент, который невозможно интерпретировать как дату или время |

**Returns:** $this - 


<a name="method_getItn" class="anchor"></a>
#### public getItn() : string|null

```php
public getItn() : string|null
```

**Summary**

Возвращает ИНН самозанятого

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

**Returns:** string|null - ИНН самозанятого


<a name="method_setItn" class="anchor"></a>
#### public setItn() : $this

```php
public setItn(string|null $itn = null) : $this
```

**Summary**

Устанавливает ИНН самозанятого

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | itn  | ИНН самозанятого |

**Returns:** $this - 


<a name="method_getPhone" class="anchor"></a>
#### public getPhone() : string|null

```php
public getPhone() : string|null
```

**Summary**

Возвращает телефон самозанятого.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

**Returns:** string|null - Телефон самозанятого


<a name="method_setPhone" class="anchor"></a>
#### public setPhone() : $this

```php
public setPhone(string|null $phone = null) : $this
```

**Summary**

Устанавливает телефон самозанятого.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | phone  | Телефон самозанятого |

**Returns:** $this - 


<a name="method_getConfirmation" class="anchor"></a>
#### public getConfirmation() : \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation|null

```php
public getConfirmation() : \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation|null
```

**Summary**

Возвращает сценарий подтверждения.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

**Returns:** \YooKassa\Model\SelfEmployed\SelfEmployedConfirmation|null - Сценарий подтверждения


<a name="method_setConfirmation" class="anchor"></a>
#### public setConfirmation() : $this

```php
public setConfirmation(\YooKassa\Model\SelfEmployed\SelfEmployedConfirmation|array|null $confirmation = null) : $this
```

**Summary**

Устанавливает сценарий подтверждения.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\SelfEmployed\SelfEmployedConfirmation OR array OR null</code> | confirmation  | Сценарий подтверждения |

**Returns:** $this - 


<a name="method_getTest" class="anchor"></a>
#### public getTest() : bool

```php
public getTest() : bool
```

**Summary**

Возвращает признак тестовой операции.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

**Returns:** bool - Признак тестовой операции.


<a name="method_setTest" class="anchor"></a>
#### public setTest() : $this

```php
public setTest(bool $test) : $this
```

**Summary**

Устанавливает признак тестовой операции.

**Details:**
* Inherited From: [\YooKassa\Model\SelfEmployed\SelfEmployedInterface](../classes/YooKassa-Model-SelfEmployed-SelfEmployedInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">bool</code> | test  | Признак тестовой операции. |

**Returns:** $this - 




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