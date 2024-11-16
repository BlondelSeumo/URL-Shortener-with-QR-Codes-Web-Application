<?php

namespace Tests\YooKassa\Model\Receipt;

use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\IndustryDetails;
use PHPUnit\Framework\TestCase;

class IndustryDetailsTest extends TestCase
{
    protected static function getInstance($options = null)
    {
        return new IndustryDetails($options);
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param $options
     */
    public function testConstructor($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getFederalId());
        self::assertNull($instance->getDocumentNumber());
        self::assertNull($instance->getDocumentDate());
        self::assertNull($instance->getValue());

        $instance = self::getInstance($options);

        self::assertEquals($options['federal_id'], $instance->getFederalId());
        self::assertEquals($options['document_number'], $instance->getDocumentNumber());
        self::assertEquals($options['document_date'], $instance->getDocumentDate()->format(IndustryDetails::DOCUMENT_DATE_FORMAT));
        self::assertEquals($options['value'], $instance->getValue());
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
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidValue($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setValue($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidValueDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidValue($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->value = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider validArrayDataProvider
     * @param array $options
     */
    public function testGetSetDocumentNumber($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getDocumentNumber());
        self::assertNull($instance->document_number);
        $instance->setDocumentNumber($options['document_number']);
        self::assertEquals($options['document_number'], $instance->getDocumentNumber());
        self::assertEquals($options['document_number'], $instance->document_number);
    }

    /**
     * @dataProvider invalidDocumentNumberDataProvider
     * @param string $document_number
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidDocumentNumber($document_number, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setDocumentNumber($document_number);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidDocumentNumberDataProvider
     * @param string $document_number
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidDocumentNumber($document_number, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->document_number = $document_number;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider validArrayDataProvider
     * @param array $options
     */
    public function testGetSetFederalId($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getFederalId());
        self::assertNull($instance->federal_id);
        $instance->setFederalId($options['federal_id']);
        self::assertEquals($options['federal_id'], $instance->getFederalId());
        self::assertEquals($options['federal_id'], $instance->federal_id);
    }

    /**
     * @dataProvider invalidFederalIdDataProvider
     * @param string $federal_id
     * @param string $exceptionClassFederalId
     */
    public function testSetInvalidFederalId($federal_id, $exceptionClassFederalId)
    {
        $instance = self::getInstance();
        try {
            $instance->setFederalId($federal_id);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassFederalId, $e);
        }
    }

    /**
     * @dataProvider invalidFederalIdDataProvider
     * @param string $federal_id
     * @param string $exceptionClassFederalId
     */
    public function testSetterInvalidFederalId($federal_id, $exceptionClassFederalId)
    {
        $instance = self::getInstance();
        try {
            $instance->federal_id = $federal_id;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassFederalId, $e);
        }
    }

    /**
     * @dataProvider validArrayDataProvider
     * @param array $options
     */
    public function testGetSetDocumentDate($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getDocumentDate());
        self::assertNull($instance->document_date);
        $instance->setDocumentDate($options['document_date']);
        self::assertEquals($options['document_date'], $instance->getDocumentDate()->format(IndustryDetails::DOCUMENT_DATE_FORMAT));
        self::assertEquals($options['document_date'], $instance->document_date->format(IndustryDetails::DOCUMENT_DATE_FORMAT));
    }

    /**
     * @dataProvider invalidDocumentDateDataProvider
     * @param string $document_date
     * @param string $exceptionClassDocumentDate
     */
    public function testSetInvalidDocumentDate($document_date, $exceptionClassDocumentDate)
    {
        $instance = self::getInstance();
        try {
            $instance->setDocumentDate($document_date);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentDate, $e);
        }
    }

    /**
     * @dataProvider invalidDocumentDateDataProvider
     * @param string $document_date
     * @param string $exceptionClassDocumentDate
     */
    public function testSetterInvalidDocumentDate($document_date, $exceptionClassDocumentDate)
    {
        $instance = self::getInstance();
        try {
            $instance->document_date = $document_date;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentDate, $e);
        }
    }

    public function validArrayDataProvider()
    {
        $result = array();
        foreach (range(1, 10) as $i) {
            $result[$i][] = array(
                'federal_id' => Random::str(1, 5),
                'document_date' => date(IndustryDetails::DOCUMENT_DATE_FORMAT, Random::int(10000000, 29999999)),
                'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
            );
        }
        return $result;
    }

    public function invalidValueDataProvider()
    {
        $exceptionValueNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionValueNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionValueNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionValueNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionValueNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(IndustryDetails::VALUE_MAX_LENGTH + 1), $exceptionValueNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionValueNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionValueNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionValueNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionValueNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionValueNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionValueNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionValueNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    public function invalidDocumentNumberDataProvider()
    {
        $exceptionDocumentNumberNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionDocumentNumberNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionDocumentNumberNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionDocumentNumberNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionDocumentNumberNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH + 1), $exceptionDocumentNumberNamespace . 'InvalidPropertyValueException'),
            array('III',                $exceptionDocumentNumberNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionDocumentNumberNamespace . 'InvalidPropertyValueTypeException'),
            array(0.0,                  $exceptionDocumentNumberNamespace . 'InvalidPropertyValueTypeException'),
            array(0,                    $exceptionDocumentNumberNamespace . 'InvalidPropertyValueTypeException'),
            array(0.01,                 $exceptionDocumentNumberNamespace . 'InvalidPropertyValueTypeException'),
            array(true,                 $exceptionDocumentNumberNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionDocumentNumberNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    public function invalidFederalIdDataProvider()
    {
        $exceptionFederalIdNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionFederalIdNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionFederalIdNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionFederalIdNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionFederalIdNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH + 1), $exceptionFederalIdNamespace . 'InvalidPropertyValueException'),
            array('III',                $exceptionFederalIdNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionFederalIdNamespace . 'InvalidPropertyValueTypeException'),
            array(0.0,                  $exceptionFederalIdNamespace . 'InvalidPropertyValueTypeException'),
            array(0,                    $exceptionFederalIdNamespace . 'InvalidPropertyValueTypeException'),
            array(0.01,                 $exceptionFederalIdNamespace . 'InvalidPropertyValueTypeException'),
            array(true,                 $exceptionFederalIdNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionFederalIdNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    public function invalidDocumentDateDataProvider()
    {
        $exceptionDocumentDateNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionDocumentDateNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionDocumentDateNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionDocumentDateNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionDocumentDateNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH + 1), $exceptionDocumentDateNamespace . 'InvalidPropertyValueException'),
            array('III',                $exceptionDocumentDateNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionDocumentDateNamespace . 'InvalidPropertyValueTypeException'),
            array(0.0,                  $exceptionDocumentDateNamespace . 'InvalidPropertyValueTypeException'),
            array(0,                    $exceptionDocumentDateNamespace . 'InvalidPropertyValueTypeException'),
            array(0.01,                 $exceptionDocumentDateNamespace . 'InvalidPropertyValueTypeException'),
            array(true,                 $exceptionDocumentDateNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionDocumentDateNamespace . 'InvalidPropertyValueTypeException'),
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
