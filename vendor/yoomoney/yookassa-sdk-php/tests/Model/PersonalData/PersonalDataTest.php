<?php

namespace Tests\YooKassa\Model\PersonalData;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Metadata;
use YooKassa\Model\PersonalData\PersonalData;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetails;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsPartyCode;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsReasonCode;
use YooKassa\Model\PersonalData\PersonalDataStatus;
use YooKassa\Model\PersonalData\PersonalDataType;

class PersonalDataTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetId($options)
    {
        $instance = new PersonalData();

        self::assertNull($instance->getId());
        self::assertNull($instance->id);

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new PersonalData();
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
        $instance = new PersonalData();
        $instance->setId($value['id']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidId($value)
    {
        $instance = new PersonalData();
        $instance->id = $value['id'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetStatus($options)
    {
        $instance = new PersonalData();

        self::assertNull($instance->getStatus());
        self::assertNull($instance->status);

        $instance->setStatus($options['status']);
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);

        $instance = new PersonalData();
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
        $instance = new PersonalData();
        $instance->setStatus($value['status']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidStatus($value)
    {
        $instance = new PersonalData();
        $instance->status = $value['status'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetType($options)
    {
        $instance = new PersonalData();

        self::assertNull($instance->getType());
        self::assertNull($instance->type);

        $instance->setType($options['type']);
        self::assertSame($options['type'], $instance->getType());
        self::assertSame($options['type'], $instance->type);

        $instance = new PersonalData();
        $instance->type = $options['type'];
        self::assertSame($options['type'], $instance->getType());
        self::assertSame($options['type'], $instance->type);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidType($value)
    {
        $instance = new PersonalData();
        $instance->setType($value['type']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     * @expectedException \InvalidArgumentException
     */
    public function testSetterInvalidType($value)
    {
        $instance = new PersonalData();
        $instance->type = $value['type'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetCreatedAt($options)
    {
        $instance = new PersonalData();

        self::assertNull($instance->getCreatedAt());
        self::assertNull($instance->createdAt);
        self::assertNull($instance->created_at);

        $instance->setCreatedAt($options['created_at']);
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new PersonalData();
        $instance->createdAt = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new PersonalData();
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
        $instance = new PersonalData();
        $instance->setCreatedAt($value['created_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidCreatedAt($value)
    {
        $instance = new PersonalData();
        $instance->createdAt = $value['created_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidCreated_at($value)
    {
        $instance = new PersonalData();
        $instance->created_at = $value['created_at'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetExpiresAt($options)
    {
        $instance = new PersonalData();

        self::assertNull($instance->getExpiresAt());
        self::assertNull($instance->expiresAt);
        self::assertNull($instance->expires_at);

        $instance->setExpiresAt($options['expires_at']);
        if (!empty($options['expires_at'])) {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        }

        $instance = new PersonalData();
        $instance->expiresAt = $options['expires_at'];
        if (!empty($options['expires_at'])) {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        }

        $instance = new PersonalData();
        $instance->expires_at = $options['expires_at'];
        if (!empty($options['expires_at'])) {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidExpiresAt($value)
    {
        $instance = new PersonalData();
        $instance->setExpiresAt($value['expires_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidExpiresAt($value)
    {
        $instance = new PersonalData();
        $instance->expiresAt = $value['expires_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidExpires_at($value)
    {
        $instance = new PersonalData();
        $instance->expires_at = $value['expires_at'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetCancellationDetails($options)
    {
        $instance = new PersonalData();

        self::assertNull($instance->getCancellationDetails());
        self::assertNull($instance->cancellationDetails);
        self::assertNull($instance->cancellation_details);

        $instance->setCancellationDetails($options['cancellation_details']);
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);

        $instance = new PersonalData();
        $instance->cancellationDetails = $options['cancellation_details'];
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);

        $instance = new PersonalData();
        $instance->cancellation_details = $options['cancellation_details'];
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidCancellationDetails($value)
    {
        $instance = new PersonalData();
        $instance->cancellation_details = $value['cancellation_details'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetMetadata($options)
    {
        $instance = new PersonalData();

        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        if (is_array($options['metadata'])) {
            $instance->setMetadata($options['metadata']);
            self::assertSame($options['metadata'], $instance->getMetadata()->toArray());
            self::assertSame($options['metadata'], $instance->metadata->toArray());

            $instance = new PersonalData();
            $instance->metadata = $options['metadata'];
            self::assertSame($options['metadata'], $instance->getMetadata()->toArray());
            self::assertSame($options['metadata'], $instance->metadata->toArray());
        } elseif ($options['metadata'] instanceof Metadata || empty($options['metadata'])) {
            $instance->setMetadata($options['metadata']);
            self::assertSame($options['metadata'], $instance->getMetadata());
            self::assertSame($options['metadata'], $instance->metadata);

            $instance = new PersonalData();
            $instance->metadata = $options['metadata'];
            self::assertSame($options['metadata'], $instance->getMetadata());
            self::assertSame($options['metadata'], $instance->metadata);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidMetadata($value)
    {
        $instance = new PersonalData();
        $instance->metadata = $value['metadata'];
    }

    public function validDataProvider()
    {
        $result = array();
        $cancellationDetailsParties = PersonalDataCancellationDetailsPartyCode::getValidValues();
        $countCancellationDetailsParties = count($cancellationDetailsParties);
        $cancellationDetailsReasons = PersonalDataCancellationDetailsReasonCode::getValidValues();
        $countCancellationDetailsReasons = count($cancellationDetailsReasons);


        $result[] = array(
            array(
                'id' => Random::str(36, 50),
                'status' => Random::value(PersonalDataStatus::getValidValues()),
                'type' => Random::value(PersonalDataType::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, mt_rand(111111111, time())),
                'expires_at' => null,
                'metadata' => array('order_id' => '37'),
                'cancellation_details' => new PersonalDataCancellationDetails(array(
                    'party' => Random::value($cancellationDetailsParties),
                    'reason' => Random::value($cancellationDetailsReasons)
                )),
            )
        );
        $result[] = array(
            array(
                'id' => Random::str(36, 50),
                'status' => Random::value(PersonalDataStatus::getValidValues()),
                'type' => Random::value(PersonalDataType::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, mt_rand(1, time())),
                'expires_at' => date(YOOKASSA_DATE, mt_rand(1, time())),
                'metadata' => null,
                'cancellation_details' => null,
            )
        );

        for ($i = 0; $i < 20; $i++) {
            $payment = array(
                'id' => Random::str(36, 50),
                'type' => Random::value(PersonalDataType::getValidValues()),
                'status' => Random::value(PersonalDataStatus::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, mt_rand(1, time())),
                'expires_at' => date(YOOKASSA_DATE, mt_rand(1, time())),
                'metadata' => new Metadata(),
                'cancellation_details' => new PersonalDataCancellationDetails(array(
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons]
                )),
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
                    'type' => null,
                    'status' => null,
                    'created_at' => null,
                    'expires_at' => '-',
                    'cancellation_details' => 123,
                    'metadata' => 789,
                )
            ),
            array(
                array(
                    'id' => '',
                    'type' => 'null',
                    'status' => '',
                    'created_at' => array(),
                    'expires_at' => array(),
                    'cancellation_details' => new \stdClass(),
                    'metadata' => new \stdClass(),
                ),
            ),
        );
        for ($i = 0; $i < 10; $i++) {
            $personalData = array(
                'id' => Random::str($i < 5 ? mt_rand(1, 35) : mt_rand(51, 64)),
                'type' => $i % 2 ? Random::str(10) : new \stdClass(),
                'status' => Random::str(1, 35),
                'created_at' => $i === 0 ? '23423-234-32' : -Random::int(),
                'expires_at' => $i === 0 ? '23423-234-32' : -Random::int(),
                'cancellation_details' => 'null',
                'metadata' => 'null',
            );
            $result[] = array($personalData);
        }
        return $result;
    }
}
