<?php

namespace Tests\YooKassa\Model\PaymentMethod;

use YooKassa\Helpers\Random;
use YooKassa\Model\PaymentMethod\BankCardSource;
use YooKassa\Model\PaymentMethod\PaymentMethodBankCard;
use YooKassa\Model\PaymentMethod\PaymentMethodCardType;
use YooKassa\Model\PaymentMethodType;

class PaymentMethodBankCardTest extends AbstractPaymentMethodTest
{
    /**
     * @return PaymentMethodBankCard
     */
    protected function getTestInstance()
    {
        return new PaymentMethodBankCard();
    }

    /**
     * @return string
     */
    protected function getExpectedType()
    {
        return PaymentMethodType::BANK_CARD;
    }

    /**
     * @dataProvider validCardProvider
     * @param string $value
     */
    public function testGetSetCard($value)
    {
        $this->getAndSetTest($value, 'card');
    }

    /**
     * @dataProvider invalidCardDataProvider
     * @expectedException \InvalidArgumentException
     * @param mixed $value
     */
    public function testSetInvalidCard($value)
    {
        $this->getTestInstance()->setCard($value);
    }

    /**
     * @dataProvider validCardProvider
     * @param array $value
     */
    public function testGetSetLast4($value)
    {
        $instance = $this->getTestInstance();
        $instance->setCard($value['card']);
        $this->getOnlyTest($instance, $value['card'], 'last4');
    }

    /**
     * @dataProvider validCardProvider
     * @param array $value
     */
    public function testGetSetFirst6($value)
    {
        $instance = $this->getTestInstance();
        $instance->setCard($value['card']);
        $this->getOnlyTest($instance, $value['card'], 'first6');
    }

    /**
     * @dataProvider validCardProvider
     * @param array $value
     */
    public function testGetSetExpiryYear($value)
    {
        $instance = $this->getTestInstance();
        $instance->setCard($value['card']);
        $this->getOnlyTest($instance, $value['card'], 'expiryYear', 'expiry_year');
    }

    /**
     * @dataProvider validCardProvider
     * @param array $value
     */
    public function testGetSetExpiryMonth($value)
    {
        $instance = $this->getTestInstance();
        $instance->setCard($value['card']);
        $this->getOnlyTest($instance, $value['card'], 'expiryMonth', 'expiry_month');
    }

    /**
     * @dataProvider validCardProvider
     * @param array $value
     */
    public function testGetSetCardType($value)
    {
        $instance = $this->getTestInstance();
        $instance->setCard($value['card']);
        $this->getOnlyTest($instance, $value['card'], 'cardType', 'card_type');
    }

    /**
     * @dataProvider validCardProvider
     * @param array $value
     */
    public function testGetSetIssuerCountry($value)
    {
        $instance = $this->getTestInstance();
        $instance->setCard($value['card']);
        $this->getOnlyTest($instance, $value['card'], 'issuerCountry', 'issuer_country');
    }

    /**
     * @dataProvider validCardProvider
     * @param array $value
     */
    public function testGetSetIssuerName($value)
    {
        $instance = $this->getTestInstance();
        $instance->setCard($value['card']);
        $this->getOnlyTest($instance, $value['card'], 'issuerName', 'issuer_name');
    }

    /**
     * @dataProvider validCardProvider
     * @param array $value
     */
    public function testGetSetSource($value)
    {
        $instance = $this->getTestInstance();
        $instance->setCard($value['card']);
        $this->getOnlyTest($instance, $value['card'], 'source', 'source');
    }


    /**
     * @return array
     */
    public function validCardProvider()
    {
        $result = array();
        for ($i = 0; $i < 10; $i++) {
            $result[] = array(
                array(
                    'card' => array(
                        'first6' => Random::str(6, '0123456789'),
                        'last4' => Random::str(4, '0123456789'),
                        'expiry_year' => Random::int(2000, 2200),
                        'expiry_month' => Random::value($this->validExpiryMonth()),
                        'card_type' => Random::value(PaymentMethodCardType::getValidValues()),
                        'issuer_country' => Random::value($this->validIssuerCountry()),
                        'issuer_name' => Random::str(3, 35),
                        'source' => Random::value(BankCardSource::getValidValues()),
                    )
                )
            );
        }
        return $result;
    }

    /**
     * @return array
     */
    private function validExpiryMonth()
    {
        return array(
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
        );
    }

    private function validIssuerCountry()
    {
        return array(
            'RU',
            'EN',
            'UK',
            'AU',
            null,
            '',
        );
    }

    public function invalidCardDataProvider()
    {
        return array(
            array(''),
            array(null),
            array(0),
            array(1),
            array(-1),
            array(new \stdClass()),
            array(Random::str(3, '0123456789')),
            array(Random::str(5, '0123456789')),
        );
    }

    protected function getAndSetTest($value, $property, $snakeCase = null)
    {
        $getter = 'get' . ucfirst($property);
        $setter = 'set' . ucfirst($property);

        $instance = $this->getTestInstance();

        self::assertNull($instance->{$getter}());
        self::assertNull($instance->{$property});
        if ($snakeCase !== null) {
            self::assertNull($instance->{$snakeCase});
        }

        $instance->{$setter}($value);

        self::assertEquals($value, $instance->{$getter}()->toArray());
        self::assertEquals($value, $instance->{$property}->toArray());
        if ($snakeCase !== null) {
            self::assertEquals($value, $instance->{$snakeCase}->toArray());
        }

        $instance = $this->getTestInstance();

        $instance->{$property} = $value;

        self::assertEquals($value, $instance->{$getter}()->toArray());
        self::assertEquals($value, $instance->{$property}->toArray());
        if ($snakeCase !== null) {
            self::assertEquals($value, $instance->{$snakeCase}->toArray());
        }

        if ($snakeCase !== null) {
            $instance = $this->getTestInstance();

            $instance->{$snakeCase} = $value;

            self::assertEquals($value, $instance->{$getter}()->toArray());
            self::assertEquals($value, $instance->{$property}->toArray());
            self::assertEquals($value, $instance->{$snakeCase}->toArray());
        }
    }

    protected function getOnlyTest($instance, $value, $property, $snakeCase = null)
    {
        $getter = 'get' . ucfirst($property);

        if ($snakeCase !== null) {
            self::assertEquals($value[$snakeCase], $instance->{$getter}());
            self::assertEquals($value[$snakeCase], $instance->{$property});
            self::assertEquals($value[$snakeCase], $instance->{$snakeCase});
        } else {
            self::assertEquals($value[$property], $instance->{$getter}());
            self::assertEquals($value[$property], $instance->{$property});
        }
    }
}
