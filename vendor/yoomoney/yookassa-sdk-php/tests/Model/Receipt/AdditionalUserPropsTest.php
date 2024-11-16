<?php

namespace Tests\YooKassa\Model\Receipt;

use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\AdditionalUserProps;
use PHPUnit\Framework\TestCase;

class AdditionalUserPropsTest extends TestCase
{
    protected static function getInstance($options = null)
    {
        return new AdditionalUserProps($options);
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param $options
     */
    public function testConstructor($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getName());
        self::assertNull($instance->getValue());

        $instance = self::getInstance($options);

        self::assertEquals($options['value'], $instance->getValue());
        self::assertEquals($options['name'], $instance->getName());
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetValue($options)
    {
        $expected = $options['value'];

        $instance = self::getInstance();
        self::assertNull($instance->getValue());
        self::assertNull($instance->value);
        $instance->setValue($expected);
        self::assertEquals($expected, $instance->getValue());
        self::assertEquals($expected, $instance->value);

        $instance = self::getInstance();
        $instance->value = $expected;
        self::assertEquals($expected, $instance->getValue());
        self::assertEquals($expected, $instance->value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidValue($value, $exceptionClassName)
    {
        $instance = self::getInstance();
        try {
            $instance->setValue($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassName, $e);
        }
    }

    /**
     * @dataProvider invalidValueDataProvider
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidValue($value, $exceptionClassName)
    {
        $instance = self::getInstance();
        try {
            $instance->value = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassName, $e);
        }
    }

    /**
     * @dataProvider validArrayDataProvider
     * @param array $options
     */
    public function testGetSetName($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getName());
        self::assertNull($instance->name);
        $instance->setName($options['name']);
        self::assertEquals($options['name'], $instance->getName());
        self::assertEquals($options['name'], $instance->name);
    }

    /**
     * @dataProvider invalidNameDataProvider
     * @param string $name
     * @param string $exceptionClassName
     */
    public function testSetInvalidName($name, $exceptionClassName)
    {
        $instance = self::getInstance();
        try {
            $instance->setName($name);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassName, $e);
        }
    }

    /**
     * @dataProvider invalidNameDataProvider
     * @param string $name
     * @param string $exceptionClassName
     */
    public function testSetterInvalidName($name, $exceptionClassName)
    {
        $instance = self::getInstance();
        try {
            $instance->name = $name;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassName, $e);
        }
    }

    public function validArrayDataProvider()
    {
        $result = array();
        foreach (range(1, 10) as $i) {
            $result[$i][] = array(
                'value' => Random::str(1, AdditionalUserProps::VALUE_MAX_LENGTH),
                'name' => Random::str(1, AdditionalUserProps::NAME_MAX_LENGTH),
            );
        }
        return $result;
    }

    public function invalidValueDataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(AdditionalUserProps::VALUE_MAX_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    public function invalidNameDataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(AdditionalUserProps::NAME_MAX_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array('III',                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(0.01,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    public function invalidIncreaseDataProvider()
    {
        return array(
            array(1, null),
            array(1.01, ''),
            array(1.00, true),
            array(0.99, false),
            array(0.99, array()),
            array(0.99, new \stdClass()),
            array(0.99, 'test'),
            array(0.99, -1.0),
            array(0.99, -0.99),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testJsonSerialize($options)
    {
        $instance = self::getInstance($options);
        $expected = $options;
        self::assertEquals($expected, $instance->jsonSerialize());
    }
}
