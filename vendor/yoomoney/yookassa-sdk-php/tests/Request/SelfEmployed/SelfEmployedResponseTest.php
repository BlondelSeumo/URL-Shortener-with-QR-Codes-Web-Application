<?php

namespace Tests\YooKassa\Request\SelfEmployed;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationFactory;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;
use YooKassa\Model\SelfEmployed\SelfEmployedStatus;
use YooKassa\Request\SelfEmployed\SelfEmployedResponse;

class SelfEmployedResponseTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetId($options)
    {
        $instance = new SelfEmployedResponse();

        self::assertNull($instance->getId());
        self::assertNull($instance->id);

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new SelfEmployedResponse();
        $instance->id = $options['id'];
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidId($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->setId($value['id']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidId($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->id = $value['id'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetStatus($options)
    {
        $instance = new SelfEmployedResponse();

        self::assertNull($instance->getStatus());
        self::assertNull($instance->status);

        $instance->setStatus($options['status']);
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);

        $instance = new SelfEmployedResponse();
        $instance->status = $options['status'];
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidStatus($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->setStatus($value['status']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidStatus($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->status = $value['status'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetTest($options)
    {
        $instance = new SelfEmployedResponse();

        self::assertNull($instance->getTest());
        self::assertNull($instance->test);

        $instance->setTest($options['test']);
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);

        $instance = new SelfEmployedResponse();
        $instance->test = $options['test'];
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidTest($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->setTest($value['test']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     * @expectedException \InvalidArgumentException
     */
    public function testSetterInvalidTest($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->test = $value['test'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetPhone($options)
    {
        $instance = new SelfEmployedResponse();

        self::assertNull($instance->getPhone());
        self::assertNull($instance->phone);

        $instance->setPhone($options['phone']);
        self::assertSame($options['phone'], $instance->getPhone());
        self::assertSame($options['phone'], $instance->phone);

        $instance = new SelfEmployedResponse();
        $instance->phone = $options['phone'];
        self::assertSame($options['phone'], $instance->getPhone());
        self::assertSame($options['phone'], $instance->phone);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidPhone($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->setPhone($value['phone']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     * @expectedException \InvalidArgumentException
     */
    public function testSetterInvalidPhone($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->phone = $value['phone'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetItn($options)
    {
        $instance = new SelfEmployedResponse();

        self::assertNull($instance->getItn());
        self::assertNull($instance->itn);

        $instance->setItn($options['itn']);
        self::assertSame($options['itn'], $instance->getItn());
        self::assertSame($options['itn'], $instance->itn);

        $instance = new SelfEmployedResponse();
        $instance->itn = $options['itn'];
        self::assertSame($options['itn'], $instance->getItn());
        self::assertSame($options['itn'], $instance->itn);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidItn($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->setItn($value['itn']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     * @expectedException \InvalidArgumentException
     */
    public function testSetterInvalidItn($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->itn = $value['itn'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetCreatedAt($options)
    {
        $instance = new SelfEmployedResponse();

        self::assertNull($instance->getCreatedAt());
        self::assertNull($instance->createdAt);
        self::assertNull($instance->created_at);

        $instance->setCreatedAt($options['created_at']);
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new SelfEmployedResponse();
        $instance->createdAt = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new SelfEmployedResponse();
        $instance->created_at = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidCreatedAt($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->setCreatedAt($value['created_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidCreatedAt($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->createdAt = $value['created_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidCreated_at($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->created_at = $value['created_at'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetConfirmation($options)
    {
        $instance = new SelfEmployedResponse();

        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $instance->setConfirmation($options['confirmation']);
        if (is_array($options['confirmation'])) {
            self::assertSame($options['confirmation'], $instance->getConfirmation()->toArray());
            self::assertSame($options['confirmation'], $instance->confirmation->toArray());
        } else {
            self::assertSame($options['confirmation'], $instance->getConfirmation());
            self::assertSame($options['confirmation'], $instance->confirmation);
        }

        $instance = new SelfEmployedResponse();
        $instance->confirmation = $options['confirmation'];
        if (is_array($options['confirmation'])) {
            self::assertSame($options['confirmation'], $instance->getConfirmation()->toArray());
            self::assertSame($options['confirmation'], $instance->confirmation->toArray());
        } else {
            self::assertSame($options['confirmation'], $instance->getConfirmation());
            self::assertSame($options['confirmation'], $instance->confirmation);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidConfirmation($value)
    {
        $instance = new SelfEmployedResponse();
        $instance->confirmation = $value['confirmation'];
    }

    public function validDataProvider()
    {
        $result = array();
        $confirmTypes = SelfEmployedConfirmationType::getValidValues();
        $confirmFactory = new SelfEmployedConfirmationFactory();

        $result[] = array(
            array(
                'id' => Random::str(36, 50),
                'status' => Random::value(SelfEmployedStatus::getValidValues()),
                'test' => Random::bool(),
                'itn' => null,
                'phone' => Random::str(11, 11,'0123456789'),
                'created_at' => date(YOOKASSA_DATE, mt_rand(111111111, time())),
                'confirmation' => array('type' => Random::value($confirmTypes)),
            )
        );
        $result[] = array(
            array(
                'id' => Random::str(36, 50),
                'status' => Random::value(SelfEmployedStatus::getValidValues()),
                'test' => Random::bool(),
                'itn' => Random::str(11, 11,'0123456789'),
                'phone' => null,
                'created_at' => date(YOOKASSA_DATE, mt_rand(1, time())),
                'confirmation' => null,
            )
        );

        for ($i = 0; $i < 20; $i++) {
            $payment = array(
                'id' => Random::str(36, 50),
                'status' => Random::value(SelfEmployedStatus::getValidValues()),
                'test' => Random::bool(),
                'itn' => Random::str(11, 11,'0123456789'),
                'phone' => Random::str(11, 11,'0123456789'),
                'created_at' => date(YOOKASSA_DATE, mt_rand(1, time())),
                'confirmation' => $confirmFactory->factory(Random::value($confirmTypes)),
            );
            $result[] = array($payment);
        }
        return $result;
    }

    public function invalidDataProvider()
    {
        $result = array(
            array(
                array(
                    'id' => null,
                    'test' => null,
                    'status' => null,
                    'itn' => new \stdClass(),
                    'phone' => array(),
                    'created_at' => null,
                    'confirmation' => array('type' => null),
                )
            ),
            array(
                array(
                    'id' => '',
                    'test' => 'null',
                    'status' => '',
                    'itn' => array(),
                    'phone' => new \stdClass(),
                    'created_at' => array(),
                    'confirmation' => new \stdClass(),
                ),
            ),
        );
        for ($i = 0; $i < 10; $i++) {
            $selfEmployed = array(
                'id' => Random::str($i < 5 ? mt_rand(1, 35) : mt_rand(51, 64)),
                'test' => $i % 2 ? Random::str(10) : new \stdClass(),
                'status' => Random::str(1, 35),
                'phone' => $i % 2 ? new \stdClass() : array(),
                'itn' => $i % 2 ? new \stdClass() : array(),
                'created_at' => $i === 0 ? '23423-234-32' : -Random::int(),
                'confirmation' => Random::value(array(
                    array('type' => null),
                    array('type' => true),
                    array('type' => new \stdClass()),
                    array('type' => array()),
                    array('type' => 'fake'),
                )),
            );
            $result[] = array($selfEmployed);
        }
        return $result;
    }
}
