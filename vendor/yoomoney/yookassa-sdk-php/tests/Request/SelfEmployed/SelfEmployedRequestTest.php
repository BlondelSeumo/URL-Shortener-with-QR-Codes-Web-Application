<?php

namespace Tests\YooKassa\Request\SelfEmployed;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;
use YooKassa\Request\SelfEmployed\SelfEmployedRequest;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationFactory;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationRedirect;

class SelfEmployedRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testItn($options)
    {
        $instance = new SelfEmployedRequest();

        self::assertNull($instance->getItn());
        self::assertNull($instance->itn);

        $instance->setItn($options['itn']);

        self::assertSame($options['itn'], $instance->getItn());
        self::assertSame($options['itn'], $instance->itn);
    }

    /**
     * @dataProvider invalidItnDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidItn($value)
    {
        $instance = new SelfEmployedRequest();
        $instance->setItn($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testPhone($options)
    {
        $instance = new SelfEmployedRequest();

        self::assertFalse($instance->hasPhone());
        self::assertNull($instance->getPhone());
        self::assertNull($instance->phone);

        $expected = $options['phone'];

        $instance->setPhone($options['phone']);
        if (empty($options['phone'])) {
            self::assertFalse($instance->hasPhone());
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            self::assertTrue($instance->hasPhone());
            self::assertSame($expected, $instance->getPhone());
            self::assertSame($expected, $instance->phone);
        }

        $instance->setPhone(null);
        self::assertFalse($instance->hasPhone());
        self::assertNull($instance->getPhone());
        self::assertNull($instance->phone);

        $instance->phone = $options['phone'];
        if (empty($options['phone'])) {
            self::assertFalse($instance->hasPhone());
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            self::assertTrue($instance->hasPhone());
            self::assertSame($expected, $instance->getPhone());
            self::assertSame($expected, $instance->phone);
        }
    }

    /**
     * @dataProvider invalidPhoneDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidPhone($value)
    {
        $instance = new SelfEmployedRequest();
        $instance->setPhone($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testConfirmation($options)
    {
        $instance = new SelfEmployedRequest();

        self::assertFalse($instance->hasConfirmation());
        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $expected = $options['confirmation'];
        if ($expected instanceof SelfEmployedRequestConfirmation) {
            $expected = $expected->toArray();
        }

        $instance->setConfirmation($options['confirmation']);
        if (empty($options['confirmation'])) {
            self::assertFalse($instance->hasConfirmation());
            self::assertNull($instance->getConfirmation());
            self::assertNull($instance->confirmation);
        } else {
            self::assertTrue($instance->hasConfirmation());
            self::assertSame($expected, $instance->getConfirmation()->toArray());
            self::assertSame($expected, $instance->confirmation->toArray());
        }

        $instance->setConfirmation(null);
        self::assertFalse($instance->hasConfirmation());
        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $instance->confirmation = $options['confirmation'];
        if (empty($options['confirmation'])) {
            self::assertFalse($instance->hasConfirmation());
            self::assertNull($instance->getConfirmation());
            self::assertNull($instance->confirmation);
        } else {
            self::assertTrue($instance->hasConfirmation());
            self::assertSame($expected, $instance->getConfirmation()->toArray());
            self::assertSame($expected, $instance->confirmation->toArray());
        }
    }

    /**
     * @dataProvider invalidConfirmationDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidConfirmation($value)
    {
        $instance = new SelfEmployedRequest();
        $instance->setConfirmation($value);
    }

    public function testValidate()
    {
        $instance = new SelfEmployedRequest();

        self::assertFalse($instance->validate());

        $instance->setItn(null);
        self::assertFalse($instance->validate());
        $instance->setPhone('');
        self::assertFalse($instance->validate());
        $instance->setConfirmation(new SelfEmployedRequestConfirmationRedirect());
        self::assertFalse($instance->validate());

        $instance->setItn('529834813422');
        self::assertTrue($instance->validate());
    }

    public function testBuilder()
    {
        $builder = SelfEmployedRequest::builder();
        self::assertTrue($builder instanceof SelfEmployedRequestBuilder);
    }

    public function validDataProvider()
    {
        $factory = new SelfEmployedRequestConfirmationFactory();
        $result = array(
            array(
                array(
                    'itn' => Random::str(12, 12, '0123456789'),
                    'phone' => Random::str(11, 11, '0123456789'),
                    'confirmation' => null,
                ),
            ),
            array(
                array(
                    'itn' => Random::str(12, 12, '0123456789'),
                    'phone' => Random::str(11, 11, '0123456789'),
                    'confirmation' => '',
                ),
            ),
        );
        for ($i = 0; $i < 10; $i++) {
            $request = array(
                'itn' => Random::str(12, 12, '0123456789'),
                'phone' => Random::str(11, 11, '0123456789'),
                'confirmation' => $factory->factoryFromArray(array('type' => Random::value(SelfEmployedConfirmationType::getValidValues()))),
            );
            $result[] = array($request);
        }
        return $result;
    }

    public function invalidItnDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
        );
    }

    public function invalidPhoneDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
        );
    }

    public function invalidConfirmationDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
            array(Random::str(10)),
        );
    }
}
