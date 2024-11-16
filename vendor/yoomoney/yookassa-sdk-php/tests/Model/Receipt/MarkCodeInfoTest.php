<?php

namespace Tests\YooKassa\Model\Receipt;

use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\MarkCodeInfo;
use PHPUnit\Framework\TestCase;

class MarkCodeInfoTest extends TestCase
{
    protected static function getInstance($options = null)
    {
        return new MarkCodeInfo($options);
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param $options
     */
    public function testConstructor($options)
    {
        $instance = self::getInstance();

        self::assertNull($instance->getMarkCodeRaw());
        self::assertNull($instance->getUnknown());

        $instance = self::getInstance($options);

        self::assertEquals($options['mark_code_raw'], $instance->getMarkCodeRaw());
        self::assertEquals($options['unknown'], $instance->getUnknown());
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetMarkCodeRaw($options)
    {
        $expected = $options['mark_code_raw'];

        $instance = self::getInstance();
        self::assertNull($instance->getMarkCodeRaw());
        self::assertNull($instance->mark_code_raw);
        $instance->setMarkCodeRaw($expected);
        self::assertEquals($expected, $instance->getMarkCodeRaw());
        self::assertEquals($expected, $instance->mark_code_raw);

        $instance = self::getInstance();
        $instance->mark_code_raw = $expected;
        self::assertEquals($expected, $instance->getMarkCodeRaw());
        self::assertEquals($expected, $instance->mark_code_raw);
    }

    /**
     * @dataProvider invalidMarkCodeRawDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidMarkCodeRaw($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setMarkCodeRaw($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidMarkCodeRawDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidMarkCodeRaw($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->mark_code_raw = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidMarkCodeRawDataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::int(-100, MarkCodeInfo::MIN_LENGTH - 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetUnknown($options)
    {
        $expected = $options['unknown'];

        $instance = self::getInstance();
        self::assertNull($instance->getUnknown());
        self::assertNull($instance->unknown);
        $instance->setUnknown($expected);
        self::assertEquals($expected, $instance->getUnknown());
        self::assertEquals($expected, $instance->unknown);

        $instance = self::getInstance();
        $instance->unknown = $expected;
        self::assertEquals($expected, $instance->getUnknown());
        self::assertEquals($expected, $instance->unknown);
    }

    /**
     * @dataProvider invalidUnknownDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidUnknown($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setUnknown($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidUnknownDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidUnknown($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->unknown = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidUnknownDataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_UNKNOWN_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetEan8($options)
    {
        $expected = $options['ean_8'];

        $instance = self::getInstance();
        self::assertNull($instance->getEan8());
        self::assertNull($instance->ean_8);
        $instance->setEan8($expected);
        self::assertEquals($expected, $instance->getEan8());
        self::assertEquals($expected, $instance->ean_8);

        $instance = self::getInstance();
        $instance->ean_8 = $expected;
        self::assertEquals($expected, $instance->getEan8());
        self::assertEquals($expected, $instance->ean_8);
    }

    /**
     * @dataProvider invalidEan8DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidEan8($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setEan8($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidEan8DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidEan8($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->ean_8 = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidEan8DataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_EAN_8_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetEan13($options)
    {
        $expected = $options['ean_13'];

        $instance = self::getInstance();
        self::assertNull($instance->getEan13());
        self::assertNull($instance->ean_13);
        $instance->setEan13($expected);
        self::assertEquals($expected, $instance->getEan13());
        self::assertEquals($expected, $instance->ean_13);

        $instance = self::getInstance();
        $instance->ean_13 = $expected;
        self::assertEquals($expected, $instance->getEan13());
        self::assertEquals($expected, $instance->ean_13);
    }

    /**
     * @dataProvider invalidEan13DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidEan13($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setEan13($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidEan13DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidEan13($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->ean_13 = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidEan13DataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_EAN_13_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetItf14($options)
    {
        $expected = $options['itf_14'];

        $instance = self::getInstance();
        self::assertNull($instance->getItf14());
        self::assertNull($instance->itf_14);
        $instance->setItf14($expected);
        self::assertEquals($expected, $instance->getItf14());
        self::assertEquals($expected, $instance->itf_14);

        $instance = self::getInstance();
        $instance->itf_14 = $expected;
        self::assertEquals($expected, $instance->getItf14());
        self::assertEquals($expected, $instance->itf_14);
    }

    /**
     * @dataProvider invalidItf14DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidItf14($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setItf14($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidItf14DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidItf14($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->itf_14 = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidItf14DataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_ITF_14_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetGs10($options)
    {
        $expected = $options['gs_10'];

        $instance = self::getInstance();
        self::assertNull($instance->getGs10());
        self::assertNull($instance->gs_10);
        $instance->setGs10($expected);
        self::assertEquals($expected, $instance->getGs10());
        self::assertEquals($expected, $instance->gs_10);

        $instance = self::getInstance();
        $instance->gs_10 = $expected;
        self::assertEquals($expected, $instance->getGs10());
        self::assertEquals($expected, $instance->gs_10);
    }

    /**
     * @dataProvider invalidGs10DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidGs10($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setGs10($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidGs10DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidGs10($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->gs_10 = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidGs10DataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_GS_10_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetGs1m($options)
    {
        $expected = $options['gs_1m'];

        $instance = self::getInstance();
        self::assertNull($instance->getGs1m());
        self::assertNull($instance->gs_1m);
        $instance->setGs1m($expected);
        self::assertEquals($expected, $instance->getGs1m());
        self::assertEquals($expected, $instance->gs_1m);

        $instance = self::getInstance();
        $instance->gs_1m = $expected;
        self::assertEquals($expected, $instance->getGs1m());
        self::assertEquals($expected, $instance->gs_1m);
    }

    /**
     * @dataProvider invalidGs1mDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidGs1m($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setGs1m($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidGs1mDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidGs1m($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->gs_1m = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidGs1mDataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_GS_1M_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetShort($options)
    {
        $expected = $options['short'];

        $instance = self::getInstance();
        self::assertNull($instance->getShort());
        self::assertNull($instance->short);
        $instance->setShort($expected);
        self::assertEquals($expected, $instance->getShort());
        self::assertEquals($expected, $instance->short);

        $instance = self::getInstance();
        $instance->short = $expected;
        self::assertEquals($expected, $instance->getShort());
        self::assertEquals($expected, $instance->short);
    }

    /**
     * @dataProvider invalidShortDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidShort($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setShort($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidShortDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidShort($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->short = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidShortDataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_SHORT_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetFur($options)
    {
        $expected = $options['fur'];

        $instance = self::getInstance();
        self::assertNull($instance->getFur());
        self::assertNull($instance->fur);
        $instance->setFur($expected);
        self::assertEquals($expected, $instance->getFur());
        self::assertEquals($expected, $instance->fur);

        $instance = self::getInstance();
        $instance->fur = $expected;
        self::assertEquals($expected, $instance->getFur());
        self::assertEquals($expected, $instance->fur);
    }

    /**
     * @dataProvider invalidFurDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidFur($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setFur($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidFurDataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidFur($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->fur = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidFurDataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_FUR_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetEgais20($options)
    {
        $expected = $options['egais_20'];

        $instance = self::getInstance();
        self::assertNull($instance->getEgais20());
        self::assertNull($instance->egais_20);
        $instance->setEgais20($expected);
        self::assertEquals($expected, $instance->getEgais20());
        self::assertEquals($expected, $instance->egais_20);

        $instance = self::getInstance();
        $instance->egais_20 = $expected;
        self::assertEquals($expected, $instance->getEgais20());
        self::assertEquals($expected, $instance->egais_20);
    }

    /**
     * @dataProvider invalidEgais20DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidEgais20($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setEgais20($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidEgais20DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidEgais20($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->egais_20 = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidEgais20DataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_EGAIS_20_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param array $options
     */
    public function testGetSetEgais30($options)
    {
        $expected = $options['egais_30'];

        $instance = self::getInstance();
        self::assertNull($instance->getEgais30());
        self::assertNull($instance->egais_30);
        $instance->setEgais30($expected);
        self::assertEquals($expected, $instance->getEgais30());
        self::assertEquals($expected, $instance->egais_30);

        $instance = self::getInstance();
        $instance->egais_30 = $expected;
        self::assertEquals($expected, $instance->getEgais30());
        self::assertEquals($expected, $instance->egais_30);
    }

    /**
     * @dataProvider invalidEgais30DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetInvalidEgais30($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->setEgais30($value);
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    /**
     * @dataProvider invalidEgais30DataProvider
     * @param mixed $value
     * @param string $exceptionClassDocumentNumber
     */
    public function testSetterInvalidEgais30($value, $exceptionClassDocumentNumber)
    {
        $instance = self::getInstance();
        try {
            $instance->egais_30 = $value;
        } catch (\Exception $e) {
            self::assertInstanceOf($exceptionClassDocumentNumber, $e);
        }
    }

    public function invalidEgais30DataProvider()
    {
        $exceptionNamespace = 'YooKassa\\Common\\Exceptions\\';
        return array(
            array(null,                 $exceptionNamespace . 'EmptyPropertyValueException'),
            array('',                   $exceptionNamespace . 'EmptyPropertyValueException'),
            array(array(),              $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(fopen(__FILE__, 'r'), $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(Random::str(MarkCodeInfo::MAX_EGAIS_30_LENGTH + 1), $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-1,                   $exceptionNamespace . 'InvalidPropertyValueException'),
            array(-0.01,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.0,                  $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0,                    $exceptionNamespace . 'InvalidPropertyValueException'),
            array(0.001,                $exceptionNamespace . 'InvalidPropertyValueException'),
            array(true,                 $exceptionNamespace . 'InvalidPropertyValueTypeException'),
            array(false,                $exceptionNamespace . 'InvalidPropertyValueTypeException'),
        );
    }

    public function validArrayDataProvider()
    {
        $result = array();
        foreach (range(1, 10) as $i) {
            $result[$i][] = array(
                'mark_code_raw' => Random::str(1, 256),
                'unknown' => Random::str(1, MarkCodeInfo::MAX_UNKNOWN_LENGTH),
                'ean_8' => Random::str(MarkCodeInfo::MAX_EAN_8_LENGTH),
                'ean_13' => Random::str(MarkCodeInfo::MAX_EAN_13_LENGTH),
                'itf_14' => Random::str(MarkCodeInfo::MAX_ITF_14_LENGTH),
                'gs_10' => Random::str(MarkCodeInfo::MAX_GS_10_LENGTH),
                'gs_1m' => Random::str(MarkCodeInfo::MAX_GS_1M_LENGTH),
                'short' => Random::str(MarkCodeInfo::MAX_SHORT_LENGTH),
                'fur' => Random::str(MarkCodeInfo::MAX_FUR_LENGTH),
                'egais_20' => Random::str(MarkCodeInfo::MAX_EGAIS_20_LENGTH),
                'egais_30' => Random::str(MarkCodeInfo::MAX_EGAIS_30_LENGTH),
            );
        }
        return $result;
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
