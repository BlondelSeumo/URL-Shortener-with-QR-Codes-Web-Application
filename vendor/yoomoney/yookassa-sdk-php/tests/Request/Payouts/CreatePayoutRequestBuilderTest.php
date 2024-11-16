<?php

namespace Tests\YooKassa\Request\Payouts;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\PaymentMethodType;
use YooKassa\Model\Payout;
use YooKassa\Model\Payout\PayoutDestinationType;
use YooKassa\Request\Payouts\CreatePayoutRequestBuilder;
use YooKassa\Request\Payouts\IncomeReceiptData;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataFactory;
use YooKassa\Request\Payouts\PayoutSelfEmployedInfo;

class CreatePayoutRequestBuilderTest extends TestCase
{
    /**
     * @param null $testingProperty
     * @param null $paymentType
     * @return array
     * @throws Exception
     */
    protected function getRequiredData($testingProperty = null, $paymentType = null)
    {
        $result = array();
        $even = Random::bool();
        if ($testingProperty !== 'amount') {
            $result['amount'] = Random::int(1, 100);
        }
        if ($testingProperty !== 'payoutToken') {
            $result['payoutToken'] = $even ? Random::str(36, 50) : null;
        }
        $factory = new PayoutDestinationDataFactory();
        if ($testingProperty !== 'payoutDestinationData') {
            $result['payoutDestinationData'] = $even ? null : $factory->factory(Random::value(PayoutDestinationType::getValidValues()));
        }

        return $result;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetDeal($options)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setOptions($this->getRequiredData('deal'));
        $builder->setAmount($options['amount']);
        $builder->setDeal($options['deal']);
        $instance = $builder->build();

        if (empty($options['deal'])) {
            self::assertNull($instance->getDeal());
        } else {
            self::assertNotNull($instance->getDeal());
            self::assertEquals($options['deal'], $instance->getDeal()->toArray());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetSelfEmployed($options)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setOptions($this->getRequiredData('self_employed'));
        $builder->setAmount($options['amount']);
        $builder->setSelfEmployed($options['self_employed']);
        $instance = $builder->build();

        if (empty($options['self_employed'])) {
            self::assertNull($instance->getSelfEmployed());
        } else {
            self::assertNotNull($instance->getSelfEmployed());
            if (is_array($options['self_employed'])) {
                self::assertEquals($options['self_employed'], $instance->getSelfEmployed()->toArray());
            } else {
                self::assertEquals($options['self_employed'], $instance->getSelfEmployed());
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetReceiptData($options)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setOptions($this->getRequiredData('receipt_data'));
        $builder->setAmount($options['amount']);
        $builder->setReceiptData($options['receipt_data']);
        $instance = $builder->build();

        if (empty($options['receipt_data'])) {
            self::assertNull($instance->getReceiptData());
        } else {
            self::assertNotNull($instance->getReceiptData());
            if (is_array($options['receipt_data'])) {
                self::assertEquals($options['receipt_data'], $instance->getReceiptData()->toArray());
            } else {
                self::assertEquals($options['receipt_data'], $instance->getReceiptData());
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetAmount($options)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setOptions($this->getRequiredData('amount'));
        $instance = $builder->build($this->getRequiredData());
        self::assertNotNull($instance->getAmount());

        $builder->setAmount($options['amount']);
        $instance = $builder->build($this->getRequiredData('amount'));

        if ($options['amount'] instanceof AmountInterface) {
            self::assertEquals($options['amount']->getValue(), $instance->getAmount()->getValue());
        } else {
            self::assertEquals($options['amount'], $instance->getAmount()->getValue());
        }

        $builder->setAmount(10000)->setAmount($options['amount']);
        $instance = $builder->build($this->getRequiredData('amount'));

        if ($options['amount'] instanceof AmountInterface) {
            self::assertEquals($options['amount']->getValue(), $instance->getAmount()->getValue());
        } else {
            self::assertEquals($options['amount'], $instance->getAmount()->getValue());
        }

        if (!($options['amount'] instanceof AmountInterface)) {
            $builder->setAmount(array(
                'value'    => $options['amount'],
                'currency' => 'EUR',
            ));
            $instance = $builder->build($this->getRequiredData('amount'));
            self::assertEquals($options['amount'], $instance->getAmount()->getValue());
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @dataProvider invalidAmountDataProvider
     *
     * @param $value
     */
    public function testSetInvalidAmount($value)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setAmount($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetPayoutToken($options)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setOptions($options);
        $builder->setAmount($options['amount']);
        $builder->setPayoutToken($options['payoutToken']);
        $instance = $builder->build();

        if (empty($options['payoutToken'])) {
            self::assertNull($instance->getPayoutToken());
        } else {
            self::assertNotNull($instance->getPayoutToken());
            self::assertEquals($options['payoutToken'], $instance->getPayoutToken());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetPaymentMethodId($options)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setOptions($this->getRequiredData('paymentMethodId'));
        $builder->setAmount($options['amount']);
        $builder->setPaymentMethodId($options['paymentMethodId']);
        $instance = $builder->build();

        if (empty($options['paymentMethodId'])) {
            self::assertNull($instance->getPaymentMethodId());
        } else {
            self::assertNotNull($instance->getPaymentMethodId());
            self::assertEquals($options['paymentMethodId'], $instance->getPaymentMethodId());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetPayoutDestinationData($options)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setOptions($options);
        $builder->setAmount($options['amount']);
        $builder->setPayoutDestinationData($options['payoutDestinationData']);
        $instance = $builder->build();

        if (empty($options['payoutDestinationData'])) {
            self::assertNull($instance->getPayoutDestinationData());
        } else {
            self::assertNotNull($instance->getPayoutDestinationData());
            if (is_array($options['payoutDestinationData'])) {
                self::assertEquals($options['payoutDestinationData'], $instance->getPayoutDestinationData()->toArray());
            } else {
                self::assertEquals($options['payoutDestinationData'], $instance->getPayoutDestinationData());
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetMetadata($options)
    {
        $builder = new CreatePayoutRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNull($instance->getMetadata());

        $builder->setMetadata($options['metadata']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['metadata'])) {
            self::assertNull($instance->getMetadata());
        } else {
            self::assertEquals($options['metadata'], $instance->getMetadata()->toArray());
        }
    }

    public function invalidRecipientDataProvider()
    {
        return array(
            array(null),
            array(true),
            array(false),
            array(1),
            array(1.1),
            array('test'),
            array(new \stdClass()),
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    public function validDataProvider()
    {
        $payoutDestinationData = array(
            array(
                'type' => PaymentMethodType::YOO_MONEY,
                'account_number' => Random::str(11, 33, '0123456789')
            ),
            array(
                'type' => PaymentMethodType::BANK_CARD,
                'card' => array(
                    'number' => Random::str(16, 16, '0123456789'),
                ),
            ),
            array(
                'type' => PaymentMethodType::SBP,
                'phone' => Random::str(4, 11, '0123456789'),
                'bank_id' => Random::str(4, 12, '0123456789'),
            ),
        );

        $result = array(
            array(
                array(
                    'description'       => null,
                    'amount'            => new MonetaryAmount(Random::int(1, 1000)),
                    'currency'          => Random::value(CurrencyCode::getValidValues()),
                    'payoutToken'       => uniqid('', true),
                    'paymentMethodId'   => null,
                    'payoutDestinationData' => null,
                    'confirmation'      => null,
                    'metadata'          => null,
                    'deal' => array(
                        'id' => Random::str(36, 50),
                    ),
                    'self_employed' => null,
                    'receipt_data' => null,
                ),
            ),
            array(
                array(
                    'description'       => '',
                    'amount'            => new MonetaryAmount(Random::int(1, 1000)),
                    'currency'          => Random::value(CurrencyCode::getValidValues()),
                    'payoutToken'      => null,
                    'paymentMethodId'   => '',
                    'payoutDestinationData' => Random::value($payoutDestinationData),
                    'metadata'          => array(),
                    'deal' => array(
                        'id' => Random::str(36, 50),
                    ),
                    'self_employed' => new PayoutSelfEmployedInfo(array(
                        'id' => Random::str(36, 50),
                    )),
                    'receipt_data' => new IncomeReceiptData(array(
                        'service_name' => Random::str(36, 50),
                        'amount' => new MonetaryAmount(Random::int(1, 99)),
                    )),
                ),
            ),
        );
        for ($i = 0; $i < 10; $i++) {
            $even = (bool)($i % 2);
            $request  = array(
                'description'       => uniqid('', true),
                'amount'            => mt_rand(1, 100000),
                'currency'          => CurrencyCode::RUB,
                'paymentMethodId'   => uniqid('', true),
                'payoutToken'      => $even ? null : uniqid('', true),
                'payoutDestinationData' => $even ? Random::value($payoutDestinationData) : null,
                'metadata'          => array('test' => 'test'),
                'deal' => array(
                    'id' => Random::str(36, 50),
                ),
                'self_employed' => new PayoutSelfEmployedInfo(array(
                    'id' => Random::str(36, 50),
                )),
                'receipt_data' => array(
                    'service_name' => Random::str(36, 50),
                    'amount' => array('value' => Random::int(1, 99), 'currency' => CurrencyCode::RUB),
                ),
            );
            $result[] = array($request);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function invalidAmountDataProvider()
    {
        return array(
            array(-1),
            array(true),
            array(false),
            array(new \stdClass()),
            array(0),
        );
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws Exception
     */
    public function testSetDescription($options)
    {
        $builder = new CreatePayoutRequestBuilder();

        $builder->setDescription($options['description']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['description'])) {
            self::assertNull($instance->getDescription());
        } else {
            self::assertEquals($options['description'], $instance->getDescription());
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidTypeDescription()
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setDescription(true);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidLengthDescription()
    {
        $builder     = new CreatePayoutRequestBuilder();
        $description = Random::str(Payout::MAX_LENGTH_DESCRIPTION + 1);
        $builder->setDescription($description);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function invalidDealDataProvider()
    {
        return array(
            array(true),
            array(false),
            array(new \stdClass()),
            array(0),
            array(7),
            array(Random::int(-100, -1)),
            array(Random::int(7, 100)),
        );
    }

    /**
     * @dataProvider invalidDealDataProvider
     * @expectedException InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidDeal($value)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setDeal($value);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function invalidSelfEmployedProvider()
    {
        return array(
            array(true),
            array(false),
            array(new \stdClass()),
            array(0),
            array(7),
            array(Random::int(-100, -1)),
            array(Random::int(7, 100)),
        );
    }

    /**
     * @dataProvider invalidSelfEmployedProvider
     * @expectedException InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidSelfEmployed($value)
    {
        $builder = new CreatePayoutRequestBuilder();
        $builder->setSelfEmployed($value);
    }
}
