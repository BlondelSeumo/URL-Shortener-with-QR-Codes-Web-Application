<?php

namespace Tests\YooKassa\Model\Payout;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payout\IncomeReceipt;

class IncomeReceiptTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testGetSetAmount($options)
    {
        $instance = new IncomeReceipt();

        self::assertNull($instance->getAmount());
        self::assertNull($instance->amount);

        $instance->setAmount($options['amount']);
        if (empty($options['amount'])) {
            self::assertNull($instance->getAmount());
            self::assertNull($instance->amount);
        } else {
            if (is_array($options['amount'])) {
                self::assertEquals($options['amount'], $instance->getAmount()->toArray());
                self::assertEquals($options['amount'], $instance->amount->toArray());
            } else {
                self::assertEquals($options['amount'], $instance->getAmount());
                self::assertEquals($options['amount'], $instance->amount);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testSetterAmount($options)
    {
        $instance = new IncomeReceipt();

        self::assertNull($instance->getAmount());
        self::assertNull($instance->amount);

        $instance->amount = $options['amount'];
        if (empty($options['amount'])) {
            self::assertNull($instance->getAmount());
            self::assertNull($instance->amount);
        } else {
            if (is_array($options['amount'])) {
                self::assertEquals($options['amount'], $instance->getAmount()->toArray());
                self::assertEquals($options['amount'], $instance->amount->toArray());
            } else {
                self::assertEquals($options['amount'], $instance->getAmount());
                self::assertEquals($options['amount'], $instance->amount);
            }
        }
    }

    /**
     * @dataProvider invalidAmountProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidAmount($value)
    {
        $instance = new IncomeReceipt();
        $instance->setAmount($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testGetSetServiceName($options)
    {
        $instance = new IncomeReceipt();

        self::assertNull($instance->getServiceName());
        self::assertNull($instance->service_name);
        self::assertNull($instance->serviceName);

        $instance->setServiceName($options['service_name']);
        self::assertEquals($options['service_name'], $instance->getServiceName());
        self::assertEquals($options['service_name'], $instance->service_name);
        self::assertEquals($options['service_name'], $instance->serviceName);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testSetterServiceName($options)
    {
        $instance = new IncomeReceipt();

        self::assertNull($instance->getServiceName());
        self::assertNull($instance->service_name);
        self::assertNull($instance->serviceName);

        $instance->service_name = $options['service_name'];
        self::assertEquals($options['service_name'], $instance->getServiceName());
        self::assertEquals($options['service_name'], $instance->service_name);
        self::assertEquals($options['service_name'], $instance->serviceName);

        $instance->serviceName = $options['service_name'];
        self::assertEquals($options['service_name'], $instance->getServiceName());
        self::assertEquals($options['service_name'], $instance->service_name);
        self::assertEquals($options['service_name'], $instance->serviceName);
    }

    /**
     * @dataProvider invalidServiceNameProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidServiceName($value)
    {
        $instance = new IncomeReceipt();
        $instance->setServiceName($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testGetSetNpdReceiptId($options)
    {
        $instance = new IncomeReceipt();

        self::assertNull($instance->getNpdReceiptId());
        self::assertNull($instance->npd_receipt_id);
        self::assertNull($instance->npdReceiptId);

        $instance->setNpdReceiptId($options['npd_receipt_id']);
        self::assertEquals($options['npd_receipt_id'], $instance->getNpdReceiptId());
        self::assertEquals($options['npd_receipt_id'], $instance->npd_receipt_id);
        self::assertEquals($options['npd_receipt_id'], $instance->npdReceiptId);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testSetterNpdReceiptId($options)
    {
        $instance = new IncomeReceipt();

        self::assertNull($instance->getNpdReceiptId());
        self::assertNull($instance->npd_receipt_id);
        self::assertNull($instance->npdReceiptId);

        $instance->npd_receipt_id = $options['npd_receipt_id'];
        self::assertEquals($options['npd_receipt_id'], $instance->getNpdReceiptId());
        self::assertEquals($options['npd_receipt_id'], $instance->npd_receipt_id);
        self::assertEquals($options['npd_receipt_id'], $instance->npdReceiptId);

        $instance->npdReceiptId = $options['npd_receipt_id'];
        self::assertEquals($options['npd_receipt_id'], $instance->getNpdReceiptId());
        self::assertEquals($options['npd_receipt_id'], $instance->npd_receipt_id);
        self::assertEquals($options['npd_receipt_id'], $instance->npdReceiptId);
    }

    /**
     * @dataProvider invalidStringProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidNpdReceiptId($value)
    {
        $instance = new IncomeReceipt();
        $instance->setNpdReceiptId($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testGetSetUrl($options)
    {
        $instance = new IncomeReceipt();

        self::assertNull($instance->getUrl());
        self::assertNull($instance->url);

        $instance->setUrl($options['url']);
        self::assertEquals($options['url'], $instance->getUrl());
        self::assertEquals($options['url'], $instance->url);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testSetterUrl($options)
    {
        $instance = new IncomeReceipt();

        self::assertNull($instance->getUrl());
        self::assertNull($instance->url);

        $instance->url = $options['url'];
        self::assertEquals($options['url'], $instance->getUrl());
        self::assertEquals($options['url'], $instance->url);
    }

    /**
     * @dataProvider invalidStringProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidUrl($value)
    {
        $instance = new IncomeReceipt();
        $instance->setUrl($value);
    }

    public function validDataProvider()
    {
        $result = array(
            array(
                array(
                    'service_name'    => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                    'npd_receipt_id'  => null,
                    'url'             => null,
                    'amount'          => null,
                ),
            ),
            array(
                array(
                    'service_name'    => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                    'npd_receipt_id'  => '',
                    'url'             => Random::str(1, 50),
                    'amount'          => array(),
                ),
            ),
            array(
                array(
                    'service_name'    => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                    'npd_receipt_id'  => Random::str(1, 50),
                    'url'             => '',
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                ),
            ),
        );
        for ($i = 1; $i < 6; $i++) {
            $receipt = array(
                'service_name'    => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                'npd_receipt_id'  => Random::str(1, 50),
                'url'             => Random::str(1, 50),
                'amount' => array(
                    'value' => round(Random::float(0.1, 99.99), 2),
                    'currency' => Random::value(CurrencyCode::getValidValues())
                ),
            );
            $result[] = array($receipt);
        }
        return $result;
    }

    public function invalidServiceNameProvider()
    {
        return array(
            array(null),
            array(''),
            array(new \stdClass()),
            array(true),
            array(false),
            array(array()),
            array(Random::str(IncomeReceipt::MAX_LENGTH_SERVICE_NAME + 1, 60)),
        );
    }

    public function invalidStringProvider()
    {
        return array(
            array(new \stdClass()),
            array(true),
            array(false),
            array(array()),
        );
    }

    public function invalidAmountProvider()
    {
        return array(
            array(new \stdClass()),
            array(true),
            array(false),
        );
    }

}
