<?php

namespace Tests\YooKassa\Request\Payouts;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payouts\PayoutPersonalData;

class PayoutPersonalDataTest extends TestCase
{
    /**
     * @param $options
     * @return PayoutPersonalData
     */
    protected function getTestInstance($options)
    {
        return new PayoutPersonalData($options);
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
                'id' => Random::str(36, 50),
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
        $instance = new PayoutPersonalData();
        $instance->setId($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidId($value)
    {
        $instance = new PayoutPersonalData();
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
