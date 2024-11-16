<?php

namespace Tests\YooKassa\Model\PaymentMethod;

use InvalidArgumentException;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\PaymentMethod\AbstractPaymentMethod;
use YooKassa\Model\PaymentMethod\PaymentMethodSberLoan;
use YooKassa\Model\PaymentMethodType;

class PaymentMethodSberLoanTest extends AbstractPaymentMethodTest
{
    /**
     * @return AbstractPaymentMethod
     */
    protected function getTestInstance()
    {
        return new PaymentMethodSberLoan();
    }

    /**
     * @return string
     */
    protected function getExpectedType()
    {
        return PaymentMethodType::SBER_LOAN;
    }


    /**
     * @dataProvider validLoanOptionDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetLoanOption($value)
    {
        $instance = $this->getTestInstance();

        $instance->setLoanOption($value);
        self::assertEquals($value, $instance->getLoanOption());
        self::assertEquals($value, $instance->loan_option);

        $instance = $this->getTestInstance();
        $instance->loan_option = $value;
        self::assertEquals($value, $instance->getLoanOption());
        self::assertEquals($value, $instance->loan_option);
    }

    /**
     * @dataProvider invalidLoanOptionDataProvider
     * @expectedException InvalidArgumentException
     * @param mixed $value
     */
    public function testSetInvalidLoanOption($value)
    {
        $instance = $this->getTestInstance();
        $instance->setLoanOption($value);
    }

    /**
     * @dataProvider invalidLoanOptionDataProvider
     * @expectedException InvalidArgumentException
     * @param mixed $value
     */
    public function testSetterInvalidLoanOption($value)
    {
        $instance = $this->getTestInstance();
        $instance->loan_option = $value;
    }

    /**
     * @dataProvider validDiscountAmountDataProvider
     */
    public function testGetSetDiscountAmount($value)
    {
        $instance = $this->getTestInstance();

        $instance->setDiscountAmount($value);
        self::assertSame($value, $instance->getDiscountAmount());
        self::assertSame($value, $instance->discount_amount);
        self::assertSame($value, $instance->discountAmount);

        $instance = $this->getTestInstance();
        $instance->discount_amount = $value;
        self::assertSame($value, $instance->getDiscountAmount());
        self::assertSame($value, $instance->discount_amount);
        self::assertSame($value, $instance->discountAmount);

        $instance = $this->getTestInstance();
        $instance->discountAmount = $value;
        self::assertSame($value, $instance->getDiscountAmount());
        self::assertSame($value, $instance->discount_amount);
        self::assertSame($value, $instance->discountAmount);
    }

    /**
     * @dataProvider invalidDiscountAmountDataProvider
     * @expectedException InvalidArgumentException
     * @param mixed $value
     */
    public function testSetInvalidDiscountAmount($value)
    {
        $instance = $this->getTestInstance();
        $instance->setDiscountAmount($value);
    }

    /**
     * @dataProvider invalidDiscountAmountDataProvider
     * @expectedException InvalidArgumentException
     * @param mixed $value
     */
    public function testSetterInvalidDiscountAmount($value)
    {
        $instance = $this->getTestInstance();
        $instance->discount_amount = $value;
    }

    public function validLoanOptionDataProvider()
    {
        return array(
            array(null),
            array(''),
            array('loan'),
            array('installments_1'),
            array('installments_12'),
            array('installments_36'),
        );
    }

    public function invalidLoanOptionDataProvider()
    {
        return array(
            array(true),
            array('2345678901234567'),
            array('installments_'),
        );
    }

    public function validDiscountAmountDataProvider()
    {
        $result = array(
            array(null),
        );
        for ($i = 0; $i < 10; $i++) {
            $result[] = array(new MonetaryAmount(array('value' => Random::int(1, 10000), 'currency' => Random::value(CurrencyCode::getEnabledValues()))));
        }
        return $result;
    }

    public function invalidDiscountAmountDataProvider()
    {
        return array(
            array(true),
            array('2345678901234567'),
            array('installments_'),
        );
    }
}
