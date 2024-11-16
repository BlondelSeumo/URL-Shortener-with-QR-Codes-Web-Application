<?php

namespace Tests\YooKassa\Request\Payouts;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payout\IncomeReceipt;
use YooKassa\Request\Payouts\IncomeReceiptData;

class IncomeReceiptDataTest extends TestCase
{
    /**
     * @param $options
     * @return IncomeReceiptData
     */
    protected function getTestInstance($options)
    {
        return new IncomeReceiptData($options);
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetServiceName($options)
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['service_name'], $instance->getServiceName());
    }

    public function validDataProvider()
    {
        $result = array();

        for ($i = 0; $i < 10; $i++) {
            $deal = array(
                'service_name' => Random::str(36, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                'amount' => new MonetaryAmount(Random::int(1, 1000000)),
            );
            $result[] = array($deal);
        }

        return $result;
    }

    /**
     * @dataProvider invalidServiceNameDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidServiceName($value)
    {
        $instance = new IncomeReceiptData();
        $instance->setServiceName($value);
    }

    public function invalidServiceNameDataProvider()
    {
        return array(
            array(null),
            array(''),
            array(false),
            array(true),
            array(array()),
            array(Random::str(IncomeReceipt::MAX_LENGTH_SERVICE_NAME + 1, 60)),
        );
    }

    /**
     * @dataProvider invalidAmountDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidAmountToken($value)
    {
        $instance = new IncomeReceiptData();
        $instance->setAmount($value);
    }

    public function invalidAmountDataProvider()
    {
        return array(
            array(null),
            array(''),
            array(false),
            array(true),
            array(new \stdClass()),
        );
    }
}
