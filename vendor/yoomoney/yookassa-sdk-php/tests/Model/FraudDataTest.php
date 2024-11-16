<?php

namespace Tests\YooKassa\Model;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\FraudData;

class FraudDataTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetToppedUpPhone($options)
    {
        $instance = new FraudData();

        self::assertNull($instance->getToppedUpPhone());
        self::assertNull($instance->topped_up_phone);

        $instance->setToppedUpPhone($options['topped_up_phone']);
        self::assertEquals($options['topped_up_phone'], $instance->getToppedUpPhone());
        self::assertEquals($options['topped_up_phone'], $instance->topped_up_phone);

        $instance = new FraudData();
        $instance->topped_up_phone = $options['topped_up_phone'];
        self::assertEquals($options['topped_up_phone'], $instance->getToppedUpPhone());
        self::assertEquals($options['topped_up_phone'], $instance->topped_up_phone);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidToppedUpPhone($value)
    {
        $instance = new FraudData();
        $instance->setToppedUpPhone($value);
    }

    public function validDataProvider()
    {
        $result = array();
        $result[] = array(array('topped_up_phone' => null));
        $result[] = array(array('topped_up_phone' => ''));
        for ($i = 0; $i < 10; $i++) {
            $payment = array(
                'topped_up_phone' => Random::str(11, 15, '0123456789'),
            );
            $result[] = array($payment);
        }
        return $result;
    }

    public function invalidDataProvider()
    {
        return array(
            array(new \stdClass()),
            array(true),
            array(false),
            array(array(123)),
            array(Random::str(16, 30, '0123456789')),
        );
    }
}
