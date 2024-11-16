<?php

namespace Tests\YooKassa\Request\Payouts;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\PayoutDealInfo;
use YooKassa\Model\Metadata;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payout\PayoutDestinationType;
use YooKassa\Request\Payouts\CreatePayoutRequest;
use YooKassa\Request\Payouts\CreatePayoutRequestSerializer;
use YooKassa\Request\Payouts\IncomeReceiptData;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataFactory;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataYooMoney;
use YooKassa\Request\Payouts\PayoutPersonalData;
use YooKassa\Request\Payouts\PayoutSelfEmployedInfo;

class CreatePayoutRequestSerializerTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     */
    public function testSerialize($options)
    {
        $serializer = new CreatePayoutRequestSerializer();
        $instance   = CreatePayoutRequest::builder()->build($options);
        $data       = $serializer->serialize($instance);

        $request = new CreatePayoutRequest($options);
        $expected = $request->toArray();

        self::assertEquals($expected, $data);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function validDataProvider()
    {
        $metadata = new Metadata();
        $metadata->test = 'test';
        $result = array(
            array(
                array(
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                    'payoutToken' => uniqid('', true),
                    'payoutDestinationData' => null,
                    'metadata' => null,
                    'description' => null,
                    'deal' => null,
                    'payment_method_id' => null,
                    'self_employed' => null,
                    'receipt_data' => null,
                    'personal_data' => null,
                ),
            ),
            array(
                array(
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                    'payoutToken' => '',
                    'payoutDestinationData' => new PayoutDestinationDataYooMoney(),
                    'metadata' => array(),
                    'description' => '',
                    'deal' => '',
                    'payment_method_id' => '',
                    'self_employed' => array(),
                    'receipt_data' => array(),
                    'personal_data' => array(),
                ),
            ),
        );
        $factory = new PayoutDestinationDataFactory();
        for ($i = 0; $i < 10; $i++) {
            $even = (bool)($i % 2);
            $request = array(
                'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                'payoutToken' => $even ? null : uniqid('', true),
                'payoutDestinationData' => $even ? $factory->factory(Random::value(PayoutDestinationType::getValidValues())) : null,
                'metadata' => $even ? $metadata : array('test' => 'test'),
                'description' => Random::str(5, 128),
                'deal' => $even ? new PayoutDealInfo(array('id' => Random::str(36, 50))) : array('id' => Random::str(36, 50)),
                'payment_method_id' => Random::str(5, 128),
                'self_employed' => $even ? new PayoutSelfEmployedInfo(array('id' => Random::str(36, 50))) : array('id' => Random::str(36, 50)),
                'receipt_data' => $even ? new IncomeReceiptData(array('service_name' => Random::str(36, 50), 'amount' => new MonetaryAmount(Random::int(1, 1000000)))) : array('service_name' => Random::str(36, 50), 'amount' => array('value' => Random::int(1, 1000000).'.00', 'currency' => CurrencyCode::RUB)),
                'personal_data' => array($even ? new PayoutPersonalData(array('id' => Random::str(36, 50))) : array('id' => Random::str(36, 50))),
            );
            $result[] = array($request);
        }

        return $result;
    }
}
