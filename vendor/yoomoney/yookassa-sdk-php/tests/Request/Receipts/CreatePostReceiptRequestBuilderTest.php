<?php

namespace Tests\YooKassa\Request\Receipts;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt\AdditionalUserProps;
use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\OperationalDetails;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\SettlementType;
use YooKassa\Model\ReceiptCustomer;
use YooKassa\Model\ReceiptCustomerInterface;
use YooKassa\Model\ReceiptType;
use YooKassa\Request\Receipts\CreatePostReceiptRequestBuilder;

class CreatePostReceiptRequestBuilderTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     */
    public function testSetItems($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $instance = $builder->build($options);

        if (empty($options['items'])) {
            self::assertNull($instance->getItems());
        } else {
            self::assertNotNull($instance->getItems());
            self::assertEquals(count($options['items']), count($instance->getItems()));
        }
    }

     /**
     * @dataProvider validDataProvider
     *
     * @param $options
     */
    public function testSetSettlements($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $instance = $builder->build($options);

        if (empty($options['settlements'])) {
            self::assertNull($instance->getSettlements());
        } else {
            self::assertNotNull($instance->getSettlements());
            self::assertEquals(count($options['settlements']), count($instance->getSettlements()));
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws \Exception
     */
    public function testSetCustomer($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $instance = $builder->build($options);

        if (empty($options['customer'])) {
            self::assertNull($instance->getCustomer());
        } else {
            self::assertNotNull($instance->getCustomer());
            if (!is_object($instance->getCustomer())) {
                self::assertEquals($options['customer'], $instance->getCustomer()->jsonSerialize());
            } else {
                self::assertTrue($instance->getCustomer() instanceof ReceiptCustomerInterface);
            }
        }
    }

    /**
     * @dataProvider invalidCustomerDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetInvalidCustomer($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $builder->setCustomer($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     */
    public function testSetType($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();

        $instance = $builder->build($options);

        if (empty($options['type'])) {
            self::assertNull($instance->getType());
        } else {
            self::assertNotNull($instance->getType());
            self::assertEquals($options['type'], $instance->getType());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     */
    public function testSetObjectId($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();

        $instance = $builder->build($options);

        if (empty($options['payment_id']) && empty($options['refund_id'])) {
            self::assertNull($instance->getObjectId());
        } else {
            self::assertNotNull($instance->getObjectId());
            if (!empty($options['payment_id'])) {
                self::assertEquals($options['payment_id'], $instance->getObjectId());
                self::assertEquals(ReceiptType::PAYMENT, $instance->getObjectType());
            }
            if (!empty($options['refund_id'])) {
                self::assertEquals($options['refund_id'], $instance->getObjectId());
                self::assertEquals(ReceiptType::REFUND, $instance->getObjectType());
            }
        }
    }

    /**
     * @dataProvider invalidVatIdDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetInvalidType($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $builder->setType($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     */
    public function testSetSend($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();

        $instance = $builder->build($options);

        if (empty($options['send'])) {
            self::assertNull($instance->getSend());
        } else {
            self::assertNotNull($instance->getSend());
            self::assertEquals($options['send'], $instance->getSend());
        }
    }

    /**
     * @dataProvider invalidBooleanDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetInvalidSend($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $builder->setType($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     */
    public function testSetTaxSystemCode($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();

        $instance = $builder->build($options);

        if (empty($options['tax_system_code'])) {
            self::assertNull($instance->getTaxSystemCode());
        } else {
            self::assertNotNull($instance->getTaxSystemCode());
            self::assertEquals($options['tax_system_code'], $instance->getTaxSystemCode());
        }
    }

    /**
     * @dataProvider invalidVatIdDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetInvalidTaxSystemId($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $builder->setTaxSystemCode($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws \Exception
     */
    public function testSetAdditionalUserProps($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $instance = $builder->build($options);

        if (empty($options['additional_user_props'])) {
            self::assertNull($instance->getAdditionalUserProps());
        } else {
            self::assertNotNull($instance->getAdditionalUserProps());
            if (!is_object($instance->getAdditionalUserProps())) {
                self::assertEquals($options['additional_user_props'], $instance->getAdditionalUserProps()->toArray());
            } else {
                self::assertTrue($instance->getAdditionalUserProps() instanceof AdditionalUserProps);
            }
        }
    }

    /**
     * @dataProvider invalidAdditionalUserPropsDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetInvalidAdditionalProps($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $builder->setAdditionalUserProps($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws \Exception
     */
    public function testSetReceiptIndustryDetails($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $instance = $builder->build($options);

        if (empty($options['receipt_industry_details'])) {
            self::assertNull($instance->getReceiptIndustryDetails());
        } else {
            self::assertNotNull($instance->getReceiptIndustryDetails());
            self::assertCount(count($options['receipt_industry_details']), $instance->getReceiptIndustryDetails());
        }
    }

    /**
     * @dataProvider invalidReceiptIndustryDetailsDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetInvalidReceiptIndustryDetails($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $builder->setReceiptIndustryDetails($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     * @throws \Exception
     */
    public function testSetReceiptOperationalDetails($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $instance = $builder->build($options);

        if (empty($options['receipt_operational_details'])) {
            self::assertNull($instance->getReceiptOperationalDetails());
        } else {
            self::assertNotNull($instance->getReceiptOperationalDetails());
            if (!is_object($instance->getReceiptOperationalDetails())) {
                self::assertEquals($options['receipt_operational_details'], $instance->getReceiptOperationalDetails()->toArray());
            } else {
                self::assertTrue($instance->getReceiptOperationalDetails() instanceof OperationalDetails);
            }
        }
    }

    /**
     * @dataProvider invalidReceiptOperationalDetailsDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetInvalidReceiptOperationalDetails($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $builder->setReceiptOperationalDetails($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $value
     */
    public function testSetOnBehalfOf($options)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $instance = $builder->build($options);

        if (empty($options['on_behalf_of'])) {
            self::assertNull($instance->getOnBehalfOf());
        } else {
            self::assertNotNull($instance->getOnBehalfOf());
            self::assertEquals($options['on_behalf_of'], $instance->getOnBehalfOf());
        }
    }

    /**
     * @dataProvider setAmountDataProvider
     * @param $value
     */
    public function testSetAmount($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $result = $builder->setAmount($value);
        self::assertNotNull($result);
        self::assertTrue($result instanceof CreatePostReceiptRequestBuilder);
    }

    /**
     * @dataProvider validDataProvider
     * @param $value
     */
    public function testSetCurrency($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();
        $builder->setItems($value['items']);
        $result = $builder->setCurrency(Random::value(CurrencyCode::getValidValues()));
        self::assertNotNull($result);
        self::assertTrue($result instanceof CreatePostReceiptRequestBuilder);
    }

    /**
     * @dataProvider validDataProvider
     * @param $value
     */
    public function testAddItem($value)
    {
        $builder = new CreatePostReceiptRequestBuilder();

        foreach ($value['items'] as $item) {
            $result = $builder->addItem($item);
            self::assertNotNull($result);
            self::assertTrue($result instanceof CreatePostReceiptRequestBuilder);
        }
    }


    public function setAmountDataProvider()
    {
        return array(
            array(
                new MonetaryAmount(
                    round(Random::float(0.1, 99.99), 2),
                    Random::value(CurrencyCode::getValidValues())
                )
            ),
            array(
                array(
                    'value' => round(Random::float(0.1, 99.99), 2),
                    'currency' => Random::value(CurrencyCode::getValidValues())
                )
            ),
            array('100')
        );
    }

    public function validDataProvider()
    {
        $type = Random::value(ReceiptType::getEnabledValues());
        $result = array(
            array(
                array(
                    'customer' => new ReceiptCustomer(),
                    'items' => array(
                        array(
                            'description' => Random::str(128),
                            'quantity' => Random::int(1, 10),
                            'amount' => array(
                                'value' => round(Random::float(0.1, 99.99), 2),
                                'currency' => Random::value(CurrencyCode::getValidValues())
                            ),
                            'vat_code' => Random::int(1, 6),
                            'payment_subject' => Random::value(PaymentSubject::getValidValues()),
                            'payment_mode' => Random::value(PaymentMode::getValidValues()),
                            'product_code' => Random::str(2, 96, '1234567890ABCDEF '),
                            'country_of_origin_code' => 'RU',
                            'customs_declaration_number' => Random::str(32),
                            'excise' => Random::float(0.0, 99.99),
                        )
                    ),
                    'tax_system_code' => Random::int(1, 6),
                    'additional_user_props' => null,
                    'receipt_industry_details' => null,
                    'receipt_operational_details' => null,
                    'type' => 'payment',
                    'send' => true,
                    'settlements' => array(
                        array(
                            'type' => Random::value(SettlementType::getValidValues()),
                            'amount' => array(
                                'value' => round(Random::float(0.1, 99.99), 2),
                                'currency' => Random::value(CurrencyCode::getValidValues())
                            )
                        )
                    ),
                    $type . '_id' => uniqid('', true)
                ),
            ),
        );
        for ($i = 0; $i < 10; $i++) {
            $type = Random::value(ReceiptType::getEnabledValues());
            $request = array(
                'customer' => array(
                    'full_name' => Random::str(128),
                    'email' => Random::str(128),
                    'phone' => Random::str(4, 12, '1234567890'),
                    'inn' => '1234567890',
                ),
                'items' => array(
                    array(
                        'description' => Random::str(128),
                        'quantity' => Random::int(1, 10),
                        'amount' => array(
                            'value' => round(Random::float(0.1, 99.99), 2),
                            'currency' => Random::value(CurrencyCode::getValidValues())
                        ),
                        'vat_code' => Random::int(1, 6),
                        'payment_subject' => Random::value(PaymentSubject::getValidValues()),
                        'payment_mode' => Random::value(PaymentMode::getValidValues()),
                        'product_code' => Random::str(2, 96, '0123456789ABCDEF '),
                        'country_of_origin_code' => 'RU',
                        'customs_declaration_number' => Random::str(32),
                        'excise' => round(Random::float(0.0, 99.99), 2),
                    )
                ),
                'tax_system_code' => Random::int(1, 6),
                'additional_user_props' => array(
                    'name' => Random::str(1, AdditionalUserProps::NAME_MAX_LENGTH),
                    'value' => Random::str(1, AdditionalUserProps::VALUE_MAX_LENGTH),
                ),
                'receipt_industry_details' => array(
                    array(
                        'federal_id' => Random::str(1, 255),
                        'document_date' => date(IndustryDetails::DOCUMENT_DATE_FORMAT),
                        'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                        'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
                    ),
                ),
                'receipt_operational_details' => array(
                    'operation_id' => Random::int(0, OperationalDetails::OPERATION_ID_MAX_LENGTH),
                    'value' => Random::str(1, OperationalDetails::VALUE_MAX_LENGTH),
                    'created_at' => date(YOOKASSA_DATE),
                ),
                'type' => $type,
                'send' => true,
                'on_behalf_of' => Random::int(99999, 999999),
                'settlements' => array(
                    array(
                        'type' => Random::value(SettlementType::getValidValues()),
                        'amount' => array(
                            'value' => round(Random::float(0.1, 99.99), 2),
                            'currency' => Random::value(CurrencyCode::getValidValues())
                        )
                    )
                ),
                $type . '_id' => uniqid('', true)
            );
            $result[] = array($request);
        }
        return $result;
    }

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

    public function invalidCustomerDataProvider()
    {
        return array(
            array(true),
            array(false),
            array(Random::str(1, 100)),
        );
    }

    public function invalidVatIdDataProvider()
    {
        return array(
            array(array()),
            array(true),
            array(false),
            array(new \stdClass()),
            array(0),
            array(7),
            array(Random::int(-100, -1)),
            array(Random::int(7, 100)),
        );
    }

    public function invalidBooleanDataProvider()
    {
        return array(
            array(array()),
            array(new \stdClass()),
            array('test'),
        );
    }

    public function invalidAdditionalUserPropsDataProvider()
    {
        return array(
            array(new \stdClass()),
            array('test'),
            array(array(
                'name' => null,
                'value' => null,
            )),
            array(array(
                'name' => Random::str(1, AdditionalUserProps::NAME_MAX_LENGTH),
                'value' => null,
            )),
            array(array(
                'name' => null,
                'value' => Random::str(1, AdditionalUserProps::VALUE_MAX_LENGTH),
            )),
            array(array(
                'name' => Random::str(AdditionalUserProps::NAME_MAX_LENGTH + 1),
                'value' => Random::str(1, AdditionalUserProps::VALUE_MAX_LENGTH),
            )),
            array(array(
                'name' => Random::str(1, AdditionalUserProps::NAME_MAX_LENGTH),
                'value' => Random::str(AdditionalUserProps::VALUE_MAX_LENGTH + 1),
            )),
        );
    }

    public function invalidReceiptOperationalDetailsDataProvider()
    {
        return array(
            array(new \stdClass()),
            array(true),
            array(Random::str(1, 100)),
        );
    }

    public function invalidReceiptIndustryDetailsDataProvider()
    {
        return array(
            array(new \stdClass()),
            array(
                array(
                    new \stdClass(),
                    new \stdClass(),
                )
            ),
            array(true),
            array(Random::str(1, 100)),
        );
    }
}
