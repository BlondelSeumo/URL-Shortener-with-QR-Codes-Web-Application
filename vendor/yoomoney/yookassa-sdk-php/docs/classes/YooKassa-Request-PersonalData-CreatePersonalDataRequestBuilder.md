# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder
### Namespace: [\YooKassa\Request\PersonalData](../namespaces/yookassa-request-personaldata.md)
---
**Summary:**

Класс билдера объектов запросов к API на создание платежа


---
### Examples
Пример использования билдера

```php
try {
    $builder = \YooKassa\Request\Payments\CreatePaymentRequest::builder();
    $builder->setAmount(100)
            ->setCurrency(\YooKassa\Model\CurrencyCode::RUB)
            ->setCapture(true)
            ->setDescription('Оплата заказа 112233')
            ->setMetadata(array(
                'cms_name'       => 'yoo_api_test',
                'order_id'       => '112233',
                'language'       => 'ru',
                'transaction_id' => '123-456-789',
            ));

    // Устанавливаем страницу для редиректа после оплаты
    $builder->setConfirmation(array(
        'type'      => \YooKassa\Model\ConfirmationType::REDIRECT,
        'returnUrl' => 'https://merchant-site.ru/payment-return-page',
    ));

    // Можем установить конкретный способ оплаты
    $builder->setPaymentMethodData(\YooKassa\Model\PaymentMethodType::BANK_CARD);

    // Составляем чек
    $builder->setReceiptEmail('john.doe@merchant.com');
    $builder->setReceiptPhone('71111111111');
    // Добавим товар
    $builder->addReceiptItem(
        'Платок Gucci',
        3000,
        1.0,
        2,
        'full_payment',
        'commodity'
    );
    // Добавим доставку
    $builder->addReceiptShipping(
        'Delivery/Shipping/Доставка',
        100,
        1,
        \YooKassa\Model\Receipt\PaymentMode::FULL_PAYMENT,
        \YooKassa\Model\Receipt\PaymentSubject::SERVICE
    );

    // Можно добавить распределение денег по магазинам
    $builder->setTransfers(array(
        array(
            'account_id' => 123456,
            'amount' => array(
                array(
                    'value' => 1000,
                    'currency' => \YooKassa\Model\CurrencyCode::RUB
                )
            ),
        ),
        array(
            'account_id' => 654321,
            'amount' => array(
                array(
                    'value' => 2000,
                    'currency' => \YooKassa\Model\CurrencyCode::RUB
                )
            ),
        )
    ));

    // Создаем объект запроса
    $request = $builder->build();

    // Можно изменить данные, если нужно
    $request->setDescription($request->getDescription() . ' - merchant comment');

    $idempotenceKey = uniqid('', true);
    $response = $client->createPayment($request, $idempotenceKey);
} catch (\Exception $e) {
    $response = $e;
}

var_dump($response);

```

---
### Constants
* No constants found

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| protected | [$currentObject](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md#property_currentObject) |  | Собираемый объект запроса |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [__construct()](../classes/YooKassa-Common-AbstractRequestBuilder.md#method___construct) |  | Конструктор, инициализирует пустой запрос, который в будущем начнём собирать |
| public | [build()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md#method_build) |  | Строит и возвращает объект запроса для отправки в API ЮKassa |
| public | [setFirstName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md#method_setFirstName) |  | Устанавливает имя пользователя. |
| public | [setLastName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md#method_setLastName) |  | Устанавливает фамилию пользователя. |
| public | [setMetadata()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md#method_setMetadata) |  | Устанавливает метаданные, привязанные к платежу |
| public | [setMiddleName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md#method_setMiddleName) |  | Устанавливает отчество пользователя. |
| public | [setOptions()](../classes/YooKassa-Common-AbstractRequestBuilder.md#method_setOptions) |  | Устанавливает свойства запроса из массива |
| public | [setType()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md#method_setType) |  | Устанавливает тип персональных данных. |
| protected | [initCurrentObject()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md#method_initCurrentObject) |  | Инициализирует объект запроса, который в дальнейшем будет собираться билдером |

---
### Details
* File: [lib/Request/PersonalData/CreatePersonalDataRequestBuilder.php](../../lib/Request/PersonalData/CreatePersonalDataRequestBuilder.php)
* Package: YooKassa
* Class Hierarchy: 
  * [\YooKassa\Common\AbstractRequestBuilder](../classes/YooKassa-Common-AbstractRequestBuilder.md)
  * \YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder

---
## Properties
<a name="property_currentObject"></a>
#### protected $currentObject : \YooKassa\Request\PersonalData\CreatePersonalDataRequest
---
**Summary**

Собираемый объект запроса

**Type:** <a href="../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.html"><abbr title="\YooKassa\Request\PersonalData\CreatePersonalDataRequest">CreatePersonalDataRequest</abbr></a>

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
#### public build() : \YooKassa\Request\PersonalData\CreatePersonalDataRequestInterface|\YooKassa\Request\PersonalData\CreatePersonalDataRequest|\YooKassa\Common\AbstractRequest

```php
public build(array|null $options = null) : \YooKassa\Request\PersonalData\CreatePersonalDataRequestInterface|\YooKassa\Request\PersonalData\CreatePersonalDataRequest|\YooKassa\Common\AbstractRequest
```

**Summary**

Строит и возвращает объект запроса для отправки в API ЮKassa

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR null</code> | options  | Массив параметров для установки в объект запроса |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidRequestException | Выбрасывается если собрать объект запроса не удалось |

**Returns:** \YooKassa\Request\PersonalData\CreatePersonalDataRequestInterface|\YooKassa\Request\PersonalData\CreatePersonalDataRequest|\YooKassa\Common\AbstractRequest - Инстанс объекта запроса


<a name="method_setFirstName" class="anchor"></a>
#### public setFirstName() : self

```php
public setFirstName(string $value) : self
```

**Summary**

Устанавливает имя пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Имя пользователя. |

**Returns:** self - 


<a name="method_setLastName" class="anchor"></a>
#### public setLastName() : self

```php
public setLastName(string $value) : self
```

**Summary**

Устанавливает фамилию пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Фамилия пользователя. |

**Returns:** self - 


<a name="method_setMetadata" class="anchor"></a>
#### public setMetadata() : \YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder

```php
public setMetadata(\YooKassa\Model\Metadata|array|null $value) : \YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder
```

**Summary**

Устанавливает метаданные, привязанные к платежу

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\Metadata OR array OR null</code> | value  | Метаданные платежа, устанавливаемые мерчантом |

##### Throws:
| Type | Description |
| ---- | ----------- |
| \YooKassa\Common\Exceptions\InvalidPropertyValueTypeException | Выбрасывается если переданные данные не удалось интерпретировать как метаданные платежа |

**Returns:** \YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder - Инстанс текущего билдера


<a name="method_setMiddleName" class="anchor"></a>
#### public setMiddleName() : self

```php
public setMiddleName(string $value) : self
```

**Summary**

Устанавливает отчество пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Отчество пользователя. |

**Returns:** self - 


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


<a name="method_setType" class="anchor"></a>
#### public setType() : self

```php
public setType(string $value) : self
```

**Summary**

Устанавливает тип персональных данных.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Тип персональных данных |

**Returns:** self - Инстанс билдера запросов


<a name="method_initCurrentObject" class="anchor"></a>
#### protected initCurrentObject() : \YooKassa\Request\PersonalData\CreatePersonalDataRequest

```php
protected initCurrentObject() : \YooKassa\Request\PersonalData\CreatePersonalDataRequest
```

**Summary**

Инициализирует объект запроса, который в дальнейшем будет собираться билдером

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestBuilder.md)

**Returns:** \YooKassa\Request\PersonalData\CreatePersonalDataRequest - Инстанс собираемого объекта запроса к API



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