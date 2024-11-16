<?php

/*
 * The MIT License
 *
 * Copyright (c) 2024 "YooMoney", NBСO LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace YooKassa\Model\PaymentMethod;

use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Model\PaymentMethodType;

/**
 * Класс, описывающий метод оплаты банковской картой
 *
 * @property string $type Тип объекта
 * @property string $card Данные банковской карты
 */
class PaymentMethodBankCard extends AbstractPaymentMethod
{
    /**
     * @var int Длина кода страны по ISO 3166 https://www.iso.org/obp/ui/#iso:pub:PUB500001:en
     * @deprecated Будет удален в следующих версиях
     */
    const ISO_3166_CODE_LENGTH = 2;

    /**
     * @var BankCard Данные банковской карты
     */
    private $_card;

    public function __construct()
    {
        $this->setType(PaymentMethodType::BANK_CARD);
    }

    /**
     * Возвращает последние 4 цифры номера карты
     * @deprecated Будет удален в следующих версиях
     * @return string Последние 4 цифры номера карты
     */
    public function getLast4()
    {
        return $this->getCard() ? $this->getCard()->getLast4() : null;
    }

    /**
     * Возвращает первые 6 цифр номера карты
     * @deprecated Будет удален в следующих версиях
     * @return string Первые 6 цифр номера карты
     * @since 1.0.14
     */
    public function getFirst6()
    {
        return $this->getCard() ? $this->getCard()->getFirst6() : null;
    }

    /**
     * Возвращает срок действия, год
     * @deprecated Будет удален в следующих версиях
     * @return string Срок действия, год
     */
    public function getExpiryYear()
    {
        return $this->getCard() ? $this->getCard()->getExpiryYear() : null;
    }

    /**
     * Возвращает срок действия, месяц
     * @deprecated Будет удален в следующих версиях
     * @return string Срок действия, месяц
     */
    public function getExpiryMonth()
    {
        return $this->getCard() ? $this->getCard()->getExpiryMonth() : null;
    }

    /**
     * Возвращает тип банковской карты
     * @deprecated Будет удален в следующих версиях
     * @return string Тип банковской карты
     */
    public function getCardType()
    {
        return $this->getCard() ? $this->getCard()->getCardType() : null;
    }

    /**
     * Возвращает код страны, в которой выпущена карта. Передается в формате ISO-3166 alpha-2
     * @deprecated Будет удален в следующих версиях
     * @return string Код страны, в которой выпущена карта
     */
    public function getIssuerCountry()
    {
        return $this->getCard() ? $this->getCard()->getIssuerCountry() : null;
    }

    /**
     * Возвращает наименование банка, выпустившего карту
     * @deprecated Будет удален в следующих версиях
     * @return string Наименование банка, выпустившего карту.
     */
    public function getIssuerName()
    {
        return $this->getCard() ? $this->getCard()->getIssuerName() : null;
    }

    /**
     * Возвращает источник данных банковской карты
     * @deprecated Будет удален в следующих версиях
     * @return string Источник данных банковской карты
     */
    public function getSource()
    {
        return $this->getCard() ? $this->getCard()->getSource() : null;
    }

    /**
     * Возвращает данные банковской карты
     * @return BankCard Данные банковской карты
     */
    public function getCard()
    {
        return $this->_card;
    }

    /**
     * Устанавливает данные банковской карты
     * @param BankCard|array $value Данные банковской карты
     */
    public function setCard($value)
    {
        if ($value === null || $value === '') {
            throw new EmptyPropertyValueException('Empty card value', 0, 'PaymentMethodBankCard.card');
        }

        if (is_array($value)) {
            $this->_card = new BankCard($value);
        } elseif ($value instanceof BankCard) {
            $this->_card = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid card value type',
                0,
                'PaymentMethodBankCard.card',
                $value
            );
        }
    }

    #[\ReturnTypeWillChange]
    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $return = parent::jsonSerialize();
        foreach (array('first6','last4','expiry_year','expiry_month','card_type','issuer_country','issuer_name','source') as $key) {
            unset($return[$key]);
        }
        return $return;
    }
}
