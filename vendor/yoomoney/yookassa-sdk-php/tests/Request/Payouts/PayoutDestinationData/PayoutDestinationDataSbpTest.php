<?php

namespace Tests\YooKassa\Request\Payouts\PayoutDestinationData;

use YooKassa\Helpers\Random;
use YooKassa\Model\PaymentMethodType;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataSbp;

class PayoutDestinationDataSbpTest extends AbstractPayoutDestinationDataTest
{
    /**
     * @return PayoutDestinationDataSbp
     */
    protected function getTestInstance()
    {
        return new PayoutDestinationDataSbp();
    }

    /**
     * @return string
     */
    protected function getExpectedType()
    {
        return PaymentMethodType::SBP;
    }

    /**
     * @dataProvider validPhoneDataProvider
     * @param string $value
     */
    public function testGetSetPhone($value)
    {
        $instance = $this->getTestInstance();

        self::assertNull($instance->getPhone());
        self::assertNull($instance->phone);

        $instance->setPhone($value);
        if ($value === null || $value === '') {
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getPhone());
            self::assertEquals($expected, $instance->phone);
        }

        $instance = $this->getTestInstance();
        $instance->phone = $value;
        if ($value === null || $value === '') {
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getPhone());
            self::assertEquals($expected, $instance->phone);
        }
    }

    /**
     * @dataProvider invalidPhoneDataProvider
     * @expectedException \InvalidArgumentException
     * @param mixed $value
     */
    public function testSetInvalidPhone($value)
    {
        $this->getTestInstance()->setPhone($value);
    }

    /**
     * @dataProvider invalidPhoneDataProvider
     * @expectedException \InvalidArgumentException
     * @param mixed $value
     */
    public function testSetterInvalidPhone($value)
    {
        $this->getTestInstance()->phone = $value;
    }

    /**
     * @dataProvider validBankIdDataProvider
     * @param string $value
     */
    public function testGetSetBankId($value)
    {
        $instance = $this->getTestInstance();

        self::assertNull($instance->getBankId());
        self::assertNull($instance->bankId);
        self::assertNull($instance->bank_id);

        $instance->setBankId($value);
        if ($value === null || $value === '') {
            self::assertNull($instance->getBankId());
            self::assertNull($instance->bankId);
            self::assertNull($instance->bank_id);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getBankId());
            self::assertEquals($expected, $instance->bankId);
            self::assertEquals($expected, $instance->bank_id);
        }

        $instance = $this->getTestInstance();
        $instance->bankId = $value;
        if ($value === null || $value === '') {
            self::assertNull($instance->getBankId());
            self::assertNull($instance->bankId);
            self::assertNull($instance->bank_id);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getBankId());
            self::assertEquals($expected, $instance->bankId);
            self::assertEquals($expected, $instance->bank_id);
        }
    }

    /**
     * @dataProvider invalidBankIdDataProvider
     * @expectedException \InvalidArgumentException
     * @param mixed $value
     */
    public function testSetInvalidBankId($value)
    {
        $this->getTestInstance()->setBankId($value);
    }

    /**
     * @dataProvider invalidBankIdDataProvider
     * @expectedException \InvalidArgumentException
     * @param mixed $value
     */
    public function testSetterInvalidBankId($value)
    {
        $this->getTestInstance()->bankId = $value;
    }

    public function validPhoneDataProvider()
    {
        return array(
            array(Random::str(4, 16, '0123456789')),
            array(Random::str(4, 16, '0123456789')),
        );
    }

    public function invalidPhoneDataProvider()
    {
        return array(
            array(array()),
            array(null),
            array(''),
            array(true),
            array(new \stdClass()),
            array(new \DateTime()),
        );
    }

    public function validBankIdDataProvider()
    {
        return array(
            array(Random::str(7, 12, '0123456789')),
            array(Random::str(7, 12, '0123456789')),
        );
    }

    public function invalidBankIdDataProvider()
    {
        return array(
            array(array()),
            array(null),
            array(''),
            array(true),
            array(new \stdClass()),
            array(new \DateTime()),
            array(Random::str(13)),
        );
    }
}
