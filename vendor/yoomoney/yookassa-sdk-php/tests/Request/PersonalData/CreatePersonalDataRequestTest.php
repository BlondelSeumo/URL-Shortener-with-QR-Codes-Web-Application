<?php

namespace Tests\YooKassa\Request\PersonalData;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Metadata;
use YooKassa\Model\PersonalData\PersonalDataType;
use YooKassa\Request\PersonalData\CreatePersonalDataRequest;
use YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder;

class CreatePersonalDataRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testLastName($options)
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasLastName());
        self::assertNull($instance->getLastName());
        self::assertNull($instance->last_name);

        $expected = $options['last_name'];

        $instance->setLastName($options['last_name']);
        if (empty($options['last_name'])) {
            self::assertFalse($instance->hasLastName());
            self::assertNull($instance->getLastName());
            self::assertNull($instance->last_name);
        } else {
            self::assertTrue($instance->hasLastName());
            self::assertSame($expected, $instance->getLastName());
            self::assertSame($expected, $instance->last_name);
        }

        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasLastName());
        self::assertNull($instance->getLastName());
        self::assertNull($instance->last_name);

        $instance->last_name = $options['last_name'];
        if (empty($options['last_name'])) {
            self::assertFalse($instance->hasLastName());
            self::assertNull($instance->getLastName());
            self::assertNull($instance->last_name);
        } else {
            self::assertTrue($instance->hasLastName());
            self::assertSame($expected, $instance->getLastName());
            self::assertSame($expected, $instance->last_name);
        }
    }

    /**
     * @dataProvider invalidLastNameDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidLastName($value)
    {
        $instance = new CreatePersonalDataRequest();
        $instance->setLastName($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testFirstName($options)
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasFirstName());
        self::assertNull($instance->getFirstName());
        self::assertNull($instance->first_name);

        $expected = $options['first_name'];

        $instance->setFirstName($options['first_name']);
        if (empty($options['first_name'])) {
            self::assertFalse($instance->hasFirstName());
            self::assertNull($instance->getFirstName());
            self::assertNull($instance->first_name);
        } else {
            self::assertTrue($instance->hasFirstName());
            self::assertSame($expected, $instance->getFirstName());
            self::assertSame($expected, $instance->first_name);
        }

        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasFirstName());
        self::assertNull($instance->getFirstName());
        self::assertNull($instance->first_name);

        $instance->first_name = $options['first_name'];
        if (empty($options['first_name'])) {
            self::assertFalse($instance->hasFirstName());
            self::assertNull($instance->getFirstName());
            self::assertNull($instance->first_name);
        } else {
            self::assertTrue($instance->hasFirstName());
            self::assertSame($expected, $instance->getFirstName());
            self::assertSame($expected, $instance->first_name);
        }
    }

    /**
     * @dataProvider invalidFirstNameDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidFirstName($value)
    {
        $instance = new CreatePersonalDataRequest();
        $instance->setFirstName($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testMiddleName($options)
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasMiddleName());
        self::assertNull($instance->getMiddleName());
        self::assertNull($instance->middle_name);

        $expected = $options['middle_name'];

        $instance->setMiddleName($options['middle_name']);
        if (empty($options['middle_name'])) {
            self::assertFalse($instance->hasMiddleName());
            self::assertNull($instance->getMiddleName());
            self::assertNull($instance->middle_name);
        } else {
            self::assertTrue($instance->hasMiddleName());
            self::assertSame($expected, $instance->getMiddleName());
            self::assertSame($expected, $instance->middle_name);
        }

        $instance->setMiddleName(null);
        self::assertFalse($instance->hasMiddleName());
        self::assertNull($instance->getMiddleName());
        self::assertNull($instance->middle_name);

        $instance->middle_name = $options['middle_name'];
        if (empty($options['middle_name'])) {
            self::assertFalse($instance->hasMiddleName());
            self::assertNull($instance->getMiddleName());
            self::assertNull($instance->middle_name);
        } else {
            self::assertTrue($instance->hasMiddleName());
            self::assertSame($expected, $instance->getMiddleName());
            self::assertSame($expected, $instance->middle_name);
        }
    }

    /**
     * @dataProvider invalidMiddleNameDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidMiddleName($value)
    {
        $instance = new CreatePersonalDataRequest();
        $instance->setMiddleName($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testMetadata($options)
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasMetadata());
        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $expected = $options['metadata'];
        if ($expected instanceof Metadata) {
            $expected = $expected->toArray();
        }

        $instance->setMetadata($options['metadata']);
        if (empty($options['metadata'])) {
            self::assertFalse($instance->hasMetadata());
            self::assertNull($instance->getMetadata());
            self::assertNull($instance->metadata);
        } else {
            self::assertTrue($instance->hasMetadata());
            self::assertSame($expected, $instance->getMetadata()->toArray());
            self::assertSame($expected, $instance->metadata->toArray());
        }

        $instance->setMetadata(null);
        self::assertFalse($instance->hasMetadata());
        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $instance->metadata = $options['metadata'];
        if (empty($options['metadata'])) {
            self::assertFalse($instance->hasMetadata());
            self::assertNull($instance->getMetadata());
            self::assertNull($instance->metadata);
        } else {
            self::assertTrue($instance->hasMetadata());
            self::assertSame($expected, $instance->getMetadata()->toArray());
            self::assertSame($expected, $instance->metadata->toArray());
        }
    }

    /**
     * @dataProvider invalidMetadataDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidMetadata($value)
    {
        $instance = new CreatePersonalDataRequest();
        $instance->setMetadata($value);
    }

    /**
     * @dataProvider invalidTypeDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidType($value)
    {
        $instance = new CreatePersonalDataRequest();
        $instance->setType($value);
    }

    public function testValidate()
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->validate());

        $instance->setType(Random::value(PersonalDataType::getEnabledValues()));
        self::assertFalse($instance->validate());

        $instance->setMiddleName('test');
        self::assertFalse($instance->validate());

        $instance->setLastName('test');
        self::assertFalse($instance->validate());
        $instance->setFirstName('test');
        self::assertTrue($instance->validate());

    }

    public function testBuilder()
    {
        $builder = CreatePersonalDataRequest::builder();
        self::assertTrue($builder instanceof CreatePersonalDataRequestBuilder);
    }

    public function validDataProvider()
    {
        $metadata = new Metadata();
        $metadata->test = 'test';
        $result = array(
            array(
                array(
                    'type' => Random::value(PersonalDataType::getEnabledValues()),
                    'last_name' => 'null',
                    'first_name' => 'null',
                    'middle_name' => null,
                    'metadata' => null,
                ),
            ),
            array(
                array(
                    'type' => Random::value(PersonalDataType::getEnabledValues()),
                    'last_name' => 'null',
                    'first_name' => 'null',
                    'middle_name' => '',
                    'metadata' => array(),
                ),
            ),
        );
        for ($i = 0; $i < 10; $i++) {
            $request = array(
                'type' => Random::value(PersonalDataType::getEnabledValues()),
                'last_name' => Random::str(5, CreatePersonalDataRequest::MAX_LENGTH_LAST_NAME),
                'first_name' => Random::str(5, CreatePersonalDataRequest::MAX_LENGTH_FIRST_NAME),
                'middle_name' => Random::str(5, CreatePersonalDataRequest::MAX_LENGTH_LAST_NAME),
                'metadata' => ($i % 2) ? $metadata : array('test' => 'test'),
            );
            $result[] = array($request);
        }
        return $result;
    }

    public function invalidTypeDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
            array(Random::str(10)),
        );
    }

    public function invalidMetadataDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(1),
            array(Random::str(10)),
        );
    }

    public function invalidLastNameDataProvider()
    {
        return array(
            array(null),
            array(''),
            array(false),
            array(true),
            array(new \stdClass()),
            array(Random::str(CreatePersonalDataRequest::MAX_LENGTH_LAST_NAME + 1)),
        );
    }

    public function invalidMiddleNameDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
            array(Random::str(CreatePersonalDataRequest::MAX_LENGTH_LAST_NAME + 1)),
        );
    }

    public function invalidFirstNameDataProvider()
    {
        return array(
            array(null),
            array(''),
            array(false),
            array(true),
            array(new \stdClass()),
            array(Random::str(CreatePersonalDataRequest::MAX_LENGTH_FIRST_NAME + 1)),
        );
    }

}
