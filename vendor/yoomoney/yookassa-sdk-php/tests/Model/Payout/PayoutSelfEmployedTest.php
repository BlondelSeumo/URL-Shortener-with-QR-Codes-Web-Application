<?php

namespace Tests\YooKassa\Model\Payout;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payout\PayoutSelfEmployed;
use YooKassa\Model\SelfEmployed\SelfEmployedInterface;

class PayoutSelfEmployedTest extends TestCase
{
    /**
     * @param $options
     * @return PayoutSelfEmployed
     */
    protected function getTestInstance($options)
    {
        return new PayoutSelfEmployed($options);
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetId($options)
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['id'], $instance->getId());
    }

    public function validDataProvider()
    {
        $result = array();

        for ($i = 0; $i < 10; $i++) {
            $deal = array(
                'id' => Random::str(SelfEmployedInterface::MIN_LENGTH_ID, SelfEmployedInterface::MAX_LENGTH_ID),
            );
            $result[] = array($deal);
        }

        return $result;
    }

    /**
     * @dataProvider invalidIdDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidId($value)
    {
        $instance = new PayoutSelfEmployed();
        $instance->setId($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidId($value)
    {
        $instance = new PayoutSelfEmployed();
        $instance->id = $value;
    }

    public function invalidIdDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
            array(array()),
            array(Random::str(1, 35)),
            array(Random::str(51, 60)),
        );
    }
}
