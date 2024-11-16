<?php

namespace Tests\YooKassa\Request\PersonalData;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsPartyCode;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsReasonCode;
use YooKassa\Model\PersonalData\PersonalDataStatus;
use YooKassa\Model\PersonalData\PersonalDataType;
use YooKassa\Request\PersonalData\PersonalDataResponse;

class PersonalDataResponseTest extends TestCase
{
    /**
     * @param $options
     * @return PersonalDataResponse
     */
    private function getTypeInstance($options)
    {
        return new PersonalDataResponse($options);
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetId($options)
    {
        $instance = $this->getTypeInstance($options);
        self::assertEquals($options['id'], $instance->getId());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetStatus($options)
    {
        $instance = $this->getTypeInstance($options);
        self::assertEquals($options['status'], $instance->getStatus());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetType($options)
    {
        $instance = $this->getTypeInstance($options);
        self::assertEquals($options['type'], $instance->getType());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetCreatedAt($options)
    {
        $instance = $this->getTypeInstance($options);
        if (empty($options['created_at'])) {
            self::assertNull($instance->getCreatedAt());
            self::assertNull($instance->created_at);
            self::assertNull($instance->createdAt);
        } else {
            self::assertEquals($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetExpiresAt($options)
    {
        $instance = $this->getTypeInstance($options);
        if (empty($options['expires_at'])) {
            self::assertNull($instance->getExpiresAt());
            self::assertNull($instance->expires_at);
            self::assertNull($instance->expiresAt);
        } else {
            self::assertEquals($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetCancellationDetails($options)
    {
        $instance = $this->getTypeInstance($options);
        if (empty($options['cancellation_details'])) {
            self::assertNull($instance->getCancellationDetails());
        } else {
            self::assertEquals(
                $options['cancellation_details']['party'],
                $instance->getCancellationDetails()->getParty()
            );
            self::assertEquals(
                $options['cancellation_details']['reason'],
                $instance->getCancellationDetails()->getReason()
            );
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetMetadata($options)
    {
        $instance = $this->getTypeInstance($options);
        if (empty($options['metadata'])) {
            self::assertNull($instance->getMetadata());
        } else {
            self::assertEquals($options['metadata'], $instance->getMetadata()->toArray());
        }
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
                'expires_at' => date(YOOKASSA_DATE, mt_rand(111111111, time())),
                'metadata' => array('order_id' => '37'),
                'cancellation_details' => array(
                    'party' => Random::value($cancellationDetailsParties),
                    'reason' => Random::value($cancellationDetailsReasons)
                ),
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
            $data = array(
                'id' => Random::str(36, 50),
                'status' => Random::value(PersonalDataStatus::getValidValues()),
                'type' => Random::value(PersonalDataType::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, mt_rand(1, time())),
                'expires_at' => date(YOOKASSA_DATE, mt_rand(1, time())),
                'metadata' => array(Random::str(3, 128, 'abcdefghijklmnopqrstuvwxyz') => Random::str(1, 512)),
                'cancellation_details' => array(
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons]
                ),
            );
            $result[] = array($data);
        }
        return $result;
    }
}
