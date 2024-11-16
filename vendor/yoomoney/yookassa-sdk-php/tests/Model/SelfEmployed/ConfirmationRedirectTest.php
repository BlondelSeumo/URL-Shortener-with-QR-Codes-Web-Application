<?php

namespace Tests\YooKassa\Model\SelfEmployed;

use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationRedirect;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;

class ConfirmationRedirectTest extends AbstractConfirmationTest
{
    /**
     * @return SelfEmployedConfirmationRedirect
     */
    protected function getTestInstance()
    {
        return new SelfEmployedConfirmationRedirect();
    }

    /**
     * @return string
     */
    protected function getExpectedType()
    {
        return SelfEmployedConfirmationType::REDIRECT;
    }

    /**
     * @dataProvider validUrlDataProvider
     * @param $value
     */
    public function testGetSetConfirmationUrl($value)
    {
        $instance = $this->getTestInstance();

        self::assertNull($instance->getConfirmationUrl());
        self::assertNull($instance->confirmationUrl);
        self::assertNull($instance->confirmation_url);

        $instance->setConfirmationUrl($value);
        if ($value === null || $value === '') {
            self::assertNull($instance->getConfirmationUrl());
            self::assertNull($instance->confirmationUrl);
            self::assertNull($instance->confirmation_url);
        } else {
            self::assertEquals($value, $instance->getConfirmationUrl());
            self::assertEquals($value, $instance->confirmationUrl);
            self::assertEquals($value, $instance->confirmation_url);
        }

        $instance->setConfirmationUrl(null);
        self::assertNull($instance->getConfirmationUrl());
        self::assertNull($instance->confirmationUrl);
        self::assertNull($instance->confirmation_url);

        $instance->confirmationUrl = $value;
        if ($value === null || $value === '') {
            self::assertNull($instance->getConfirmationUrl());
            self::assertNull($instance->confirmationUrl);
            self::assertNull($instance->confirmation_url);
        } else {
            self::assertEquals($value, $instance->getConfirmationUrl());
            self::assertEquals($value, $instance->confirmationUrl);
            self::assertEquals($value, $instance->confirmation_url);
        }

        $instance->confirmation_url = $value;
        if ($value === null || $value === '') {
            self::assertNull($instance->getConfirmationUrl());
            self::assertNull($instance->confirmationUrl);
            self::assertNull($instance->confirmation_url);
        } else {
            self::assertEquals($value, $instance->getConfirmationUrl());
            self::assertEquals($value, $instance->confirmationUrl);
            self::assertEquals($value, $instance->confirmation_url);
        }
    }

    /**
     * @dataProvider invalidUrlDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidConfirmationUrl($value)
    {
        $instance = $this->getTestInstance();
        $instance->setConfirmationUrl($value);
    }

    /**
     * @dataProvider invalidUrlDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidConfirmationUrl($value)
    {
        $instance = $this->getTestInstance();
        $instance->confirmationUrl = $value;
    }

    /**
     * @dataProvider invalidUrlDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidConfirmation_url($value)
    {
        $instance = $this->getTestInstance();
        $instance->confirmation_url = $value;
    }

    public function validEnforceDataProvider()
    {
        return array(
            array(true),
            array(false),
            array(null),
            array(''),
            array(0),
            array(1),
            array(100),
        );
    }

    public function validUrlDataProvider()
    {
        return array(
            array('https://test.ru'),
            array(null),
            array(''),
        );
    }

    public function invalidUrlDataProvider()
    {
        return array(
            array(true),
            array(false),
            array(array()),
            array(new \stdClass()),
        );
    }
}
