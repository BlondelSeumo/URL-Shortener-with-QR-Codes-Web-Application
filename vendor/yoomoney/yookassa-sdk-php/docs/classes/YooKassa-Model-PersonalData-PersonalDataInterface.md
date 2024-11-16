# [YooKassa API SDK](../home.md)

# Interface: PersonalDataInterface
### Namespace: [\YooKassa\Model\PersonalData](../namespaces/yookassa-model-personaldata.md)
---
**Summary:**

Класс, представляющий модель PersonalDataInterface.

**Description:**

Информация о персональных данных

---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
* [public MIN_LENGTH_ID](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#constant_MIN_LENGTH_ID)
* [public MAX_LENGTH_ID](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#constant_MAX_LENGTH_ID)

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [getCancellationDetails()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_getCancellationDetails) |  | Возвращает cancellation_details. |
| public | [getCreatedAt()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_getCreatedAt) |  | Возвращает created_at. |
| public | [getExpiresAt()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_getExpiresAt) |  | Возвращает expires_at. |
| public | [getId()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_getId) |  | Возвращает id. |
| public | [getMetadata()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_getMetadata) |  | Возвращает metadata. |
| public | [getStatus()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_getStatus) |  | Возвращает статус персональных данных. |
| public | [getType()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_getType) |  | Возвращает тип персональных данных. |
| public | [setCancellationDetails()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_setCancellationDetails) |  | Устанавливает cancellation_details. |
| public | [setCreatedAt()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_setCreatedAt) |  | Устанавливает время создания персональных данных. |
| public | [setExpiresAt()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_setExpiresAt) |  | Устанавливает срок жизни объекта персональных данных. |
| public | [setId()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_setId) |  | Устанавливает id. |
| public | [setMetadata()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_setMetadata) |  | Устанавливает metadata. |
| public | [setStatus()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_setStatus) |  | Устанавливает статус персональных данных. |
| public | [setType()](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md#method_setType) |  | Устанавливает тип персональных данных. |

---
### Details
* File: [lib/Model/PersonalData/PersonalDataInterface.php](../../lib/Model/PersonalData/PersonalDataInterface.php)
* Package: \YooKassa\Model

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| author |  | cms@yoomoney.ru |

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

Возвращает id.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

**Returns:** string - 


<a name="method_setId" class="anchor"></a>
#### public setId() : $this

```php
public setId(string $id) : $this
```

**Summary**

Устанавливает id.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | id  | Идентификатор персональных данных, сохраненных в ЮKassa. |

**Returns:** $this - 


<a name="method_getType" class="anchor"></a>
#### public getType() : string

```php
public getType() : string
```

**Summary**

Возвращает тип персональных данных.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

**Returns:** string - Тип персональных данных


<a name="method_setType" class="anchor"></a>
#### public setType() : $this

```php
public setType(string $type) : $this
```

**Summary**

Устанавливает тип персональных данных.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | type  | Тип персональных данных |

**Returns:** $this - 


<a name="method_getStatus" class="anchor"></a>
#### public getStatus() : string

```php
public getStatus() : string
```

**Summary**

Возвращает статус персональных данных.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

**Returns:** string - Статус персональных данных


<a name="method_setStatus" class="anchor"></a>
#### public setStatus() : $this

```php
public setStatus(string $status) : $this
```

**Summary**

Устанавливает статус персональных данных.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | status  | Статус персональных данных |

**Returns:** $this - 


<a name="method_getCancellationDetails" class="anchor"></a>
#### public getCancellationDetails() : \YooKassa\Model\PersonalData\PersonalDataCancellationDetails|null

```php
public getCancellationDetails() : \YooKassa\Model\PersonalData\PersonalDataCancellationDetails|null
```

**Summary**

Возвращает cancellation_details.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

**Returns:** \YooKassa\Model\PersonalData\PersonalDataCancellationDetails|null - 


<a name="method_setCancellationDetails" class="anchor"></a>
#### public setCancellationDetails() : $this

```php
public setCancellationDetails(\YooKassa\Model\PersonalData\PersonalDataCancellationDetails|array|null $cancellation_details) : $this
```

**Summary**

Устанавливает cancellation_details.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\PersonalData\PersonalDataCancellationDetails OR array OR null</code> | cancellation_details  |  |

**Returns:** $this - 


<a name="method_getCreatedAt" class="anchor"></a>
#### public getCreatedAt() : \DateTime

```php
public getCreatedAt() : \DateTime
```

**Summary**

Возвращает created_at.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

**Returns:** \DateTime - 


<a name="method_setCreatedAt" class="anchor"></a>
#### public setCreatedAt() : $this

```php
public setCreatedAt(\DateTime|string|int $created_at) : $this
```

**Summary**

Устанавливает время создания персональных данных.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\DateTime OR string OR int</code> | created_at  | Время создания персональных данных |

**Returns:** $this - 


<a name="method_getExpiresAt" class="anchor"></a>
#### public getExpiresAt() : \DateTime|null

```php
public getExpiresAt() : \DateTime|null
```

**Summary**

Возвращает expires_at.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

**Returns:** \DateTime|null - 


<a name="method_setExpiresAt" class="anchor"></a>
#### public setExpiresAt() : $this

```php
public setExpiresAt(\DateTime|string|int|null $expires_at = null) : $this
```

**Summary**

Устанавливает срок жизни объекта персональных данных.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\DateTime OR string OR int OR null</code> | expires_at  | Срок жизни объекта персональных данных |

**Returns:** $this - 


<a name="method_getMetadata" class="anchor"></a>
#### public getMetadata() : array|null

```php
public getMetadata() : array|null
```

**Summary**

Возвращает metadata.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

**Returns:** array|null - 


<a name="method_setMetadata" class="anchor"></a>
#### public setMetadata() : $this

```php
public setMetadata(\YooKassa\Model\Metadata|array|null $metadata = null) : $this
```

**Summary**

Устанавливает metadata.

**Details:**
* Inherited From: [\YooKassa\Model\PersonalData\PersonalDataInterface](../classes/YooKassa-Model-PersonalData-PersonalDataInterface.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\Metadata OR array OR null</code> | metadata  | Любые дополнительные данные, которые нужны вам для работы (например, ваш внутренний идентификатор заказа). |

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