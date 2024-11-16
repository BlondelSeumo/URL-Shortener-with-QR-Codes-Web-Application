<?php

namespace Tests\YooKassa\Model\SelfEmployed;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmation;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationFactory;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;

class ConfirmationFactoryTest extends TestCase
{
    /**
     * @return SelfEmployedConfirmationFactory
     */
    protected function getTestInstance()
    {
        return new SelfEmployedConfirmationFactory();
    }

    /**
     * @dataProvider validTypeDataProvider
     * @param string $type
     */
    public function testFactory($type)
    {
        $instance = $this->getTestInstance();
        $confirmation = $instance->factory($type);
        self::assertNotNull($confirmation);
        self::assertTrue($confirmation instanceof SelfEmployedConfirmation);
        self::assertEquals($type, $confirmation->getType());
    }

    /**
     * @dataProvider invalidTypeDataProvider
     * @expectedException \InvalidArgumentException
     * @param $type
     */
    public function testInvalidFactory($type)
    {
        $instance = $this->getTestInstance();
        $instance->factory($type);
    }

    /**
     * @dataProvider validArrayDataProvider
     * @param array $options
     */
    public function testFactoryFromArray($options)
    {
        $instance = $this->getTestInstance();
        $confirmation = $instance->factoryFromArray($options);
        self::assertNotNull($confirmation);
        self::assertTrue($confirmation instanceof SelfEmployedConfirmation);

        foreach ($options as $property => $value) {
            self::assertEquals($confirmation->{$property}, $value);
        }

        $type = $options['type'];
        unset($options['type']);
        $confirmation = $instance->factoryFromArray($options, $type);
        self::assertNotNull($confirmation);
        self::assertTrue($confirmation instanceof SelfEmployedConfirmation);

        self::assertEquals($type, $confirmation->getType());
        foreach ($options as $property => $value) {
            self::assertEquals($confirmation->{$property}, $value);
        }
    }

    /**
     * @dataProvider invalidDataArrayDataProvider
     * @expectedException \InvalidArgumentException
     * @param $options
     */
    public function testInvalidFactoryFromArray($options)
    {
        $instance = $this->getTestInstance();
        $instance->factoryFromArray($options);
    }

    public function validTypeDataProvider()
    {
        $result = array();
        foreach (SelfEmployedConfirmationType::getValidValues() as $value) {
            $result[] = array($value);
        }
        return $result;
    }

    public function invalidTypeDataProvider()
    {
        return array(
            array(''),
            array(null),
            array(0),
            array(1),
            array(-1),
            array('5'),
            array(array()),
            array(new \stdClass()),
            array(Random::str(10)),
        );
    }

    public function validArrayDataProvider()
    {
        $result = array(
            array(
                array(
                    'type' => SelfEmployedConfirmationType::REDIRECT,
                    'confirmationUrl' => Random::str(10),
                ),
            ),
            array(
                array(
                    'type' => SelfEmployedConfirmationType::REDIRECT,
                    'confirmationUrl' => Random::str(10),
                ),
            ),
            array(
                array(
                    'type' => SelfEmployedConfirmationType::REDIRECT,
                    'confirmationUrl' => Random::str(10),
                ),
            ),
            array(
                array(
                    'type' => SelfEmployedConfirmationType::REDIRECT,
                ),
            ),
            array(
                array(
                    'type' => SelfEmployedConfirmationType::REDIRECT,
                ),
            ),
            array(
                array(
                    'type' => SelfEmployedConfirmationType::REDIRECT,
                ),
            ),
        );
        foreach (SelfEmployedConfirmationType::getValidValues() as $value) {
            $result[] = array(array('type' => $value));
        }
        return $result;
    }

    public function invalidDataArrayDataProvider()
    {
        return array(
            array(array()),
            array(array('type' => 'test')),
        );
    }
}
