<?php

namespace Tests\YooKassa\Model\Receipt;

use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\MarkQuantity;
use PHPUnit\Framework\TestCase;

class MarkQuantityTest extends TestCase
{
    protected static function getInstance($options = null)
    {
        return new MarkQuantity($options);
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param $options
     */
    public function testConstructor($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getNumerator());
        self::assertNull($instance->getDenominator());

        $instance = self::getInstance($options);

        self::assertEquals($options['numerator'], $instance->getNumerator());
        self::assertEquals($options['denominator'], $instance->getDenominator());
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetDenominator($options)
    {
        $expected = $options['denominator'];

        $instance = self::getInstance();
        self::assertNull($instance->getDenominator());
        self::assertNull($instance->denominator);
        $instance->setDenominator($expected);
        self::assertEquals($expected, $instance->getDenominator());
        self::assertEquals($expected, $instance->denominator);

        $instance = self::getInstance();
        $instance->denominator = $expected;
        self::assertEquals($expected, $instance->getDenominator());
        self::assertEquals($expected, $instance->denominator);
    }

    /**
     * @dataProvider invalidDenominatorDataProvider
     * @param mixed $denominator
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidDenominator($denominator, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setDenominator($denominator);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidDenominatorDataProvider
     * @param mixed $denominator
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidDenominator($denominator, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->denominator = $denominator;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider validArrayDataProvider
     * @param array $options
     */
    public function testGetSetNumerator($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getNumerator());
        self::assertNull($instance->numerator);
        $instance->setNumerator($options['numerator']);
        self::assertEquals($options['numerator'], $instance->getNumerator());
        self::assertEquals($options['numerator'], $instance->numerator);
    }

    /**
     * @dataProvider invalidNumeratorDataProvider
     * @param string $numerator
     * @param string $exceptionClassNumerator
     */
    public function testSetInvalidNumerator($numerator, $exceptionClassNumerator)
    {
        $instance = self::getInstance();
        try {
            $instance->setNumerator($numerator);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassNumerator, $e);
        }
    }

    /**
     * @dataProvider invalidNumeratorDataProvider
     * @param string $numerator
     * @param string $exceptionClassNumerator
     */
    public function testSetterInvalidNumerator($numerator, $exceptionClassNumerator)
    {
        $instance = self::getInstance();
        try {
            $instance->numerator = $numerator;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassNumerator, $e);
        }
    }

    public function validArrayDataProvider()
    {
        $result = array();
        foreach (range(1, 10) as $i) {
            $result[$i][] = array(
                'numerator' => Random::int(MarkQuantity::MIN_VALUE, 100),
                'denominator' => Random::int(MarkQuantity::MIN_VALUE, 100),
            );
        }
        return $result;
    }

    public function invalidDenominatorDataProvider()
    {
        $exceptionDenominatorNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionDenominatorNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionDenominatorNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionDenominatorNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionDenominatorNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::int(-100, MarkQuantity::MIN_VALUE - 1), $exceptionDenominatorNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionDenominatorNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionDenominatorNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionDenominatorNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionDenominatorNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionDenominatorNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionDenominatorNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionDenominatorNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    public function invalidNumeratorDataProvider()
    {
        $exceptionNumeratorNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNumeratorNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNumeratorNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNumeratorNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNumeratorNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::int(-100, MarkQuantity::MIN_VALUE - 1), $exceptionNumeratorNamespace . 'InvalidPropertyValueException'),
            array('III',                $exceptionNumeratorNamespace . 'InvalidPropertyValueTypeException'),
            array(-0.01,                $exceptionNumeratorNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNumeratorNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNumeratorNamespace . 'InvalidPropertyValueException'),
            array(0.01,                 $exceptionNumeratorNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNumeratorNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNumeratorNamespace . 'InvalidPropertyValueTypeException'),
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
