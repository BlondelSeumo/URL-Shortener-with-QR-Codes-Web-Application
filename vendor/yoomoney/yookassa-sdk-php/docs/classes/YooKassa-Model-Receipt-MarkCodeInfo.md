# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Model\Receipt\MarkCodeInfo
### Namespace: [\YooKassa\Model\Receipt](../namespaces/yookassa-model-receipt.md)
---
**Summary:**

Class MarkCodeInfo

**Description:**

Код товара (тег в 54 ФЗ — 1163).
Обязателен при использовании протокола ФФД 1.2, если товар нужно маркировать. Должно быть заполнено хотя бы одно из полей.

---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [MIN_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MIN_LENGTH) |  |  |
| public | [MAX_UNKNOWN_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_UNKNOWN_LENGTH) |  |  |
| public | [MAX_EAN_8_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_EAN_8_LENGTH) |  |  |
| public | [MAX_EAN_13_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_EAN_13_LENGTH) |  |  |
| public | [MAX_ITF_14_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_ITF_14_LENGTH) |  |  |
| public | [MAX_GS_10_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_GS_10_LENGTH) |  |  |
| public | [MAX_GS_1M_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_GS_1M_LENGTH) |  |  |
| public | [MAX_SHORT_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_SHORT_LENGTH) |  |  |
| public | [MAX_FUR_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_FUR_LENGTH) |  |  |
| public | [MAX_EGAIS_20_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_EGAIS_20_LENGTH) |  |  |
| public | [MAX_EGAIS_30_LENGTH](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#constant_MAX_EGAIS_30_LENGTH) |  |  |

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [$ean_13](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_ean_13) |  | Код товара в формате EAN-13 (тег в 54 ФЗ — 1302) |
| public | [$ean_8](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_ean_8) |  | Код товара в формате EAN-8 (тег в 54 ФЗ — 1301) |
| public | [$egais_20](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_egais_20) |  | Код товара в формате ЕГАИС-2.0 (тег в 54 ФЗ — 1308) |
| public | [$egais_30](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_egais_30) |  | Код товара в формате ЕГАИС-3.0 (тег в 54 ФЗ — 1309) |
| public | [$fur](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_fur) |  | Контрольно-идентификационный знак мехового изделия (тег в 54 ФЗ — 1307) |
| public | [$gs_10](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_gs_10) |  | Код товара в формате GS1.0 (тег в 54 ФЗ — 1304) |
| public | [$gs_1m](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_gs_1m) |  | Код товара в формате GS1.M (тег в 54 ФЗ — 1305) |
| public | [$itf_14](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_itf_14) |  | Код товара в формате ITF-14 (тег в 54 ФЗ — 1303) |
| public | [$mark_code_raw](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_mark_code_raw) |  | Код товара в том виде, в котором он был прочитан сканером (тег в 54 ФЗ — 2000) |
| public | [$markCodeRaw](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_markCodeRaw) |  | Код товара в том виде, в котором он был прочитан сканером (тег в 54 ФЗ — 2000) |
| public | [$short](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_short) |  | Код товара в формате короткого кода маркировки (тег в 54 ФЗ — 1306) |
| public | [$unknown](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#property_unknown) |  | Нераспознанный код товара (тег в 54 ФЗ — 1300) |

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
| public | [getEan13()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getEan13) |  |  |
| public | [getEan8()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getEan8) |  |  |
| public | [getEgais20()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getEgais20) |  |  |
| public | [getEgais30()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getEgais30) |  |  |
| public | [getFur()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getFur) |  |  |
| public | [getGs10()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getGs10) |  |  |
| public | [getGs1m()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getGs1m) |  |  |
| public | [getItf14()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getItf14) |  |  |
| public | [getMarkCodeRaw()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getMarkCodeRaw) |  | Возвращает исходный код товара |
| public | [getShort()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getShort) |  |  |
| public | [getUnknown()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_getUnknown) |  |  |
| public | [jsonSerialize()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_jsonSerialize) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации |
| public | [offsetExists()](../classes/YooKassa-Common-AbstractObject.md#method_offsetExists) |  | Проверяет наличие свойства |
| public | [offsetGet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetGet) |  | Возвращает значение свойства |
| public | [offsetSet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetSet) |  | Устанавливает значение свойства |
| public | [offsetUnset()](../classes/YooKassa-Common-AbstractObject.md#method_offsetUnset) |  | Удаляет свойство |
| public | [setEan13()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setEan13) |  |  |
| public | [setEan8()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setEan8) |  |  |
| public | [setEgais20()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setEgais20) |  |  |
| public | [setEgais30()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setEgais30) |  |  |
| public | [setFur()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setFur) |  |  |
| public | [setGs10()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setGs10) |  |  |
| public | [setGs1m()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setGs1m) |  |  |
| public | [setItf14()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setItf14) |  |  |
| public | [setMarkCodeRaw()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setMarkCodeRaw) |  | Устанавливает исходный код товара |
| public | [setShort()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setShort) |  |  |
| public | [setUnknown()](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md#method_setUnknown) |  |  |
| public | [toArray()](../classes/YooKassa-Common-AbstractObject.md#method_toArray) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации Является алиасом метода AbstractObject::jsonSerialize() |
| protected | [getUnknownProperties()](../classes/YooKassa-Common-AbstractObject.md#method_getUnknownProperties) |  | Возвращает массив свойств которые не существуют, но были заданы у объекта |

---
### Details
* File: [lib/Model/Receipt/MarkCodeInfo.php](../../lib/Model/Receipt/MarkCodeInfo.php)
* Package: YooKassa
* Class Hierarchy: 
  * [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)
  * \YooKassa\Model\Receipt\MarkCodeInfo

---
## Constants
<a name="constant_MIN_LENGTH" class="anchor"></a>
###### MIN_LENGTH
```php
MIN_LENGTH = 1 : int
```


<a name="constant_MAX_UNKNOWN_LENGTH" class="anchor"></a>
###### MAX_UNKNOWN_LENGTH
```php
MAX_UNKNOWN_LENGTH = 32 : int
```


<a name="constant_MAX_EAN_8_LENGTH" class="anchor"></a>
###### MAX_EAN_8_LENGTH
```php
MAX_EAN_8_LENGTH = 8 : int
```


<a name="constant_MAX_EAN_13_LENGTH" class="anchor"></a>
###### MAX_EAN_13_LENGTH
```php
MAX_EAN_13_LENGTH = 13 : int
```


<a name="constant_MAX_ITF_14_LENGTH" class="anchor"></a>
###### MAX_ITF_14_LENGTH
```php
MAX_ITF_14_LENGTH = 14 : int
```


<a name="constant_MAX_GS_10_LENGTH" class="anchor"></a>
###### MAX_GS_10_LENGTH
```php
MAX_GS_10_LENGTH = 38 : int
```


<a name="constant_MAX_GS_1M_LENGTH" class="anchor"></a>
###### MAX_GS_1M_LENGTH
```php
MAX_GS_1M_LENGTH = 200 : int
```


<a name="constant_MAX_SHORT_LENGTH" class="anchor"></a>
###### MAX_SHORT_LENGTH
```php
MAX_SHORT_LENGTH = 38 : int
```


<a name="constant_MAX_FUR_LENGTH" class="anchor"></a>
###### MAX_FUR_LENGTH
```php
MAX_FUR_LENGTH = 20 : int
```


<a name="constant_MAX_EGAIS_20_LENGTH" class="anchor"></a>
###### MAX_EGAIS_20_LENGTH
```php
MAX_EGAIS_20_LENGTH = 23 : int
```


<a name="constant_MAX_EGAIS_30_LENGTH" class="anchor"></a>
###### MAX_EGAIS_30_LENGTH
```php
MAX_EGAIS_30_LENGTH = 14 : int
```



---
## Properties
<a name="property_ean_13"></a>
#### public $ean_13 : string
---
***Description***

Код товара в формате EAN-13 (тег в 54 ФЗ — 1302)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_ean_8"></a>
#### public $ean_8 : string
---
***Description***

Код товара в формате EAN-8 (тег в 54 ФЗ — 1301)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_egais_20"></a>
#### public $egais_20 : string
---
***Description***

Код товара в формате ЕГАИС-2.0 (тег в 54 ФЗ — 1308)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_egais_30"></a>
#### public $egais_30 : string
---
***Description***

Код товара в формате ЕГАИС-3.0 (тег в 54 ФЗ — 1309)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_fur"></a>
#### public $fur : string
---
***Description***

Контрольно-идентификационный знак мехового изделия (тег в 54 ФЗ — 1307)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_gs_10"></a>
#### public $gs_10 : string
---
***Description***

Код товара в формате GS1.0 (тег в 54 ФЗ — 1304)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_gs_1m"></a>
#### public $gs_1m : string
---
***Description***

Код товара в формате GS1.M (тег в 54 ФЗ — 1305)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_itf_14"></a>
#### public $itf_14 : string
---
***Description***

Код товара в формате ITF-14 (тег в 54 ФЗ — 1303)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_mark_code_raw"></a>
#### public $mark_code_raw : string
---
***Description***

Код товара в том виде, в котором он был прочитан сканером (тег в 54 ФЗ — 2000)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_markCodeRaw"></a>
#### public $markCodeRaw : string
---
***Description***

Код товара в том виде, в котором он был прочитан сканером (тег в 54 ФЗ — 2000)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_short"></a>
#### public $short : string
---
***Description***

Код товара в формате короткого кода маркировки (тег в 54 ФЗ — 1306)

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_unknown"></a>
#### public $unknown : string
---
***Description***

Нераспознанный код товара (тег в 54 ФЗ — 1300)

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


<a name="method_getEan13" class="anchor"></a>
#### public getEan13() : string

```php
public getEan13() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getEan8" class="anchor"></a>
#### public getEan8() : string

```php
public getEan8() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getEgais20" class="anchor"></a>
#### public getEgais20() : string

```php
public getEgais20() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getEgais30" class="anchor"></a>
#### public getEgais30() : string

```php
public getEgais30() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getFur" class="anchor"></a>
#### public getFur() : string

```php
public getFur() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getGs10" class="anchor"></a>
#### public getGs10() : string

```php
public getGs10() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getGs1m" class="anchor"></a>
#### public getGs1m() : string

```php
public getGs1m() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getItf14" class="anchor"></a>
#### public getItf14() : string

```php
public getItf14() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getMarkCodeRaw" class="anchor"></a>
#### public getMarkCodeRaw() : string

```php
public getMarkCodeRaw() : string
```

**Summary**

Возвращает исходный код товара

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - Исходный код товара


<a name="method_getShort" class="anchor"></a>
#### public getShort() : string

```php
public getShort() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_getUnknown" class="anchor"></a>
#### public getUnknown() : string

```php
public getUnknown() : string
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

**Returns:** string - 


<a name="method_jsonSerialize" class="anchor"></a>
#### public jsonSerialize() : array

```php
public jsonSerialize() : array
```

**Summary**

Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

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


<a name="method_setEan13" class="anchor"></a>
#### public setEan13() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setEan13(string $value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setEan8" class="anchor"></a>
#### public setEan8() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setEan8(string $value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setEgais20" class="anchor"></a>
#### public setEgais20() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setEgais20($value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php"></code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setEgais30" class="anchor"></a>
#### public setEgais30() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setEgais30($value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php"></code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setFur" class="anchor"></a>
#### public setFur() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setFur($value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php"></code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setGs10" class="anchor"></a>
#### public setGs10() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setGs10(string $value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setGs1m" class="anchor"></a>
#### public setGs1m() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setGs1m($value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php"></code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setItf14" class="anchor"></a>
#### public setItf14() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setItf14(string $value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setMarkCodeRaw" class="anchor"></a>
#### public setMarkCodeRaw() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setMarkCodeRaw(string $value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Summary**

Устанавливает исходный код товара

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Исходный код товара |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setShort" class="anchor"></a>
#### public setShort() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setShort($value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php"></code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


<a name="method_setUnknown" class="anchor"></a>
#### public setUnknown() : \YooKassa\Model\Receipt\MarkCodeInfo

```php
public setUnknown($value) : \YooKassa\Model\Receipt\MarkCodeInfo
```

**Details:**
* Inherited From: [\YooKassa\Model\Receipt\MarkCodeInfo](../classes/YooKassa-Model-Receipt-MarkCodeInfo.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php"></code> | value  |  |

**Returns:** \YooKassa\Model\Receipt\MarkCodeInfo - 


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