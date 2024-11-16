<?php

namespace Tests\YooKassa\Request\Receipts;

use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Helpers\ProductCode;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\Airline;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt\AgentType;
use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\MarkCodeInfo;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\ReceiptItemMeasure;
use YooKassa\Model\Supplier;
use YooKassa\Model\SupplierInterface;
use YooKassa\Request\Receipts\ReceiptResponseItem;
use PHPUnit\Framework\TestCase;

class ReceiptResponseItemTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetDescription($options)
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['description'], $instance->getDescription());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetAmount($options)
    {
        $instance = new ReceiptResponseItem($options);
        self::assertNotNull($instance->getAmount());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetVatCode($options)
    {
        $instance = new ReceiptResponseItem($options);
        $instance->setVatCode(null);
        self::assertNull($instance->getVatCode());

        $instance->setVatCode($options['vat_code']);
        self::assertNotNull($instance->getVatCode());
        self::assertEquals($options['vat_code'], $instance->getVatCode());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetExcise($options)
    {
        $instance = new ReceiptResponseItem($options);
        $instance->setExcise(null);
        self::assertNull($instance->getExcise());
        if (empty($options['excise'])) {
            self::assertNull($instance->getExcise());
        } else {
            $instance->setExcise($options['excise']);
            self::assertNotNull($instance->getExcise());
            self::assertEquals($options['excise'], $instance->getExcise());
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSupplier($options)
    {
        $instance = new ReceiptResponseItem($options);

        if (empty($options['supplier'])) {
            self::assertNull($instance->getSupplier());
        } else {
            self::assertNotNull($instance->getSupplier());
            if (!is_object($instance->getSupplier())) {
                self::assertEquals($options['supplier'], $instance->getSupplier()->jsonSerialize());
            } else {
                self::assertTrue($instance->getSupplier() instanceof SupplierInterface);
            }
            self::assertEquals($options['supplier']['name'], $instance->getSupplier()->getName());
            self::assertEquals($options['supplier']['phone'], $instance->getSupplier()->getPhone());
            self::assertEquals($options['supplier']['inn'], $instance->getSupplier()->getInn());
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetMarkCodeInfo($options)
    {
        $instance = new ReceiptResponseItem($options);

        if (empty($options['mark_code_info'])) {
            self::assertNull($instance->getMarkCodeInfo());
        } else {
            self::assertNotNull($instance->getMarkCodeInfo());
            if (!is_object($instance->getMarkCodeInfo())) {
                self::assertEquals($options['mark_code_info'], $instance->getMarkCodeInfo()->toArray());
            } else {
                self::assertTrue($instance->getMarkCodeInfo() instanceof MarkCodeInfo);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetMarkMode($options)
    {
        $instance = new ReceiptResponseItem($options);
        $instance->setMarkMode(null);
        self::assertNull($instance->getMarkMode());

        $instance->setMarkMode($options['mark_mode']);
        if (is_null($options['mark_mode'])) {
            self::assertNull($instance->getMarkMode());
        } else {
            self::assertNotNull($instance->getMarkMode());
            self::assertEquals($options['mark_mode'], $instance->getMarkMode());
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetMarkQuantity($options)
    {
        $instance = new ReceiptResponseItem($options);
        $instance->setMarkQuantity(null);
        self::assertNull($instance->getMarkQuantity());
        if (isset($options['mark_quantity'])) {
            $instance->setMarkQuantity($options['mark_quantity']);
            if (is_array($options['mark_quantity'])) {
                self::assertSame($options['mark_quantity'], $instance->getMarkQuantity()->toArray());
                self::assertSame($options['mark_quantity'], $instance->mark_quantity->toArray());
                self::assertSame($options['mark_quantity'], $instance->markQuantity->toArray());
            } else {
                self::assertNotNull($instance->getMarkQuantity());
                self::assertEquals($options['mark_quantity'], $instance->getMarkQuantity());
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetPrice($options)
    {
        $instance = new ReceiptResponseItem($options);

        if (empty($options['amount'])) {
            self::assertNull($instance->getPrice());
        } else {
            self::assertNotNull($instance->getPrice());
            if (!is_object($instance->getPrice())) {
                self::assertEquals($options['amount'], $instance->getPrice()->jsonSerialize());
            } else {
                self::assertTrue($instance->getPrice() instanceof AmountInterface);
            }
            self::assertEquals($options['amount']['value'], $instance->getPrice()->getValue());
            self::assertEquals($options['amount']['currency'], $instance->getPrice()->getCurrency());
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetQuantity($options)
    {
        $instance = new ReceiptResponseItem($options);
        self::assertNotNull($instance->getQuantity());
        self::assertEquals($options['quantity'], $instance->getQuantity());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetPaymentMode($options)
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['payment_mode'], $instance->getPaymentMode());
    }

    public function testSetPaymentSubjectData()
    {
        $instance = new ReceiptResponseItem();
        $instance->setPaymentSubject(null);
        self::assertNull($instance->getPaymentSubject());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetPaymentSubject($options)
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['payment_subject'], $instance->getPaymentSubject());
    }

    public function testSetPaymentModeData()
    {
        $instance = new ReceiptResponseItem();
        $instance->setPaymentMode(null);
        self::assertNull($instance->getPaymentMode());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetMeasure($options)
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['measure'], $instance->getMeasure());
    }

    public function testSetMeasureData()
    {
        $instance = new ReceiptResponseItem();
        $instance->setMeasure(null);
        self::assertNull($instance->getMeasure());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetCountryOfOriginCode($options)
    {
        $instance = new ReceiptResponseItem($options);
        if (!empty($options['country_of_origin_code'])) {
            self::assertEquals($options['country_of_origin_code'], $instance->getCountryOfOriginCode());
        } else {
            self::assertNull($instance->getCountryOfOriginCode());
        }
    }

    public function testSetCountryOfOriginCodeData()
    {
        $instance = new ReceiptResponseItem();
        $instance->setCountryOfOriginCode(null);
        self::assertNull($instance->getCountryOfOriginCode());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetCustomsDeclarationNumber($options)
    {
        $instance = new ReceiptResponseItem($options);
        if (!empty($options['customs_declaration_number'])) {
            self::assertEquals($options['customs_declaration_number'], $instance->getCustomsDeclarationNumber());
        } else {
            self::assertNull($instance->getCustomsDeclarationNumber());
        }
    }

    public function testSetCustomsDeclarationNumberData()
    {
        $instance = new ReceiptResponseItem();
        $instance->setCustomsDeclarationNumber(null);
        self::assertNull($instance->getCustomsDeclarationNumber());
    }

    /**
     * @dataProvider invalidCountryOfOriginCodeDataProvider
     * @param array $options
     *
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetCountryOfOriginCodeInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setCountryOfOriginCode($options);
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetExcise($options)
    {
        $instance = new ReceiptResponseItem($options);
        if (!empty($options['excise'])) {
            self::assertEquals($options['excise'], $instance->getExcise());
        } else {
            self::assertNull($instance->getExcise());
        }
    }

    public function testSetExciseData()
    {
        $instance = new ReceiptResponseItem();
        $instance->setExcise(null);
        self::assertNull($instance->getExcise());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $options
     */
    public function testGetSetProductCode($options)
    {
        $instance = new ReceiptResponseItem($options);
        if (empty($options['product_code'])) {
            self::assertNull($instance->getProductCode());
            self::assertNull($instance->productCode);
            self::assertNull($instance->product_code);
        } elseif ($options['product_code'] instanceof ProductCode) {
            self::assertEquals((string)$options['product_code'], $instance->getProductCode());
            self::assertEquals((string)$options['product_code'], $instance->productCode);
            self::assertEquals((string)$options['product_code'], $instance->product_code);
        } else {
            self::assertEquals($options['product_code'], (string)$instance->getProductCode());
            self::assertEquals($options['product_code'], (string)$instance->productCode);
            self::assertEquals($options['product_code'], (string)$instance->product_code);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param array $options
     */
    public function testGetSetPaymentSubjectIndustryDetails($options)
    {
        $instance = new ReceiptResponseItem($options);

//        self::assertNull($instance->getPaymentSubjectIndustryDetails());
//        self::assertNull($instance->payment_subject_industry_details);
//        $instance->setPaymentSubjectIndustryDetails($options);

        if (is_array($options['payment_subject_industry_details'])) {
            self::assertCount(count($options['payment_subject_industry_details']), $instance->getPaymentSubjectIndustryDetails());
            self::assertCount(count($options['payment_subject_industry_details']), $instance->payment_subject_industry_details);
            self::assertCount(count($options['payment_subject_industry_details']), $instance->paymentSubjectIndustryDetails);
        } else {
            self::assertSame($options['payment_subject_industry_details'], $instance->getPaymentSubjectIndustryDetails());
            self::assertSame($options['payment_subject_industry_details'], $instance->payment_subject_industry_details);
            self::assertSame($options['payment_subject_industry_details'], $instance->paymentSubjectIndustryDetails);
        }
    }

    public function testSetAgentTypeData()
    {
        $instance = new ReceiptResponseItem();
        $instance->setAgentType(null);
        self::assertNull($instance->getAgentType());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetAgentType($options)
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['agent_type'], $instance->getAgentType());
    }

    /**
     * @dataProvider invalidDescriptionDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetDescriptionInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setDescription($options);
    }

    /**
     * @dataProvider invalidQuantityDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetQuantityInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setQuantity($options);
    }

    /**
     * @dataProvider invalidMeasureDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetMeasureInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setMeasure($options);
    }

    /**
     * @dataProvider invalidVatCodeDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetVatCodeInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setVatCode($options);
    }

    /**
     * @dataProvider invalidExciseDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     */
    public function testSetExciseInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setExcise($options);
    }

    /**
     * @dataProvider invalidProductCodeDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     */
    public function testSetProductCodeInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setProductCode($options);
    }

    /**
     * @dataProvider invalidPaymentDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetPaymentSubjectInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setPaymentSubject($options);
    }

    /**
     * @dataProvider invalidPaymentDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetPaymentModeInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setPaymentMode($options);
    }

    /**
     * @dataProvider invalidSupplierDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetSupplierInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setSupplier($options);
    }

    /**
     * @dataProvider invalidAgentTypeDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetAgentTypeInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setAgentType($options);
    }

    /**
     * @dataProvider invalidMarkCodeInfoDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetMarkCodeInfoInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setMarkCodeInfo($options);
    }

    /**
     * @dataProvider invalidMarkModeDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetMarkModeInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setMarkMode($options);
    }

    /**
     * @dataProvider invalidMarkQuantityDataProvider
     * @param $options
     * @expectedException \InvalidArgumentException
     * @expectedException EmptyPropertyValueException
     */
    public function testSetMarkQuantityInvalidData($options)
    {
        $instance = new ReceiptResponseItem();
        $instance->setMarkQuantity($options);
    }

    /**
     * @dataProvider invalidCustomsDeclarationNumberDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetInvalidCustomsDeclarationNumber($value)
    {
        $instance = new ReceiptResponseItem();
        $instance->customsDeclarationNumber = $value;
    }

    /**
     * @dataProvider invalidCustomsDeclarationNumberDataProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $value
     */
    public function testSetterInvalidCustomsDeclarationNumber($value)
    {
        $instance = new ReceiptResponseItem();
        $instance->setCustomsDeclarationNumber($value);
    }

    public function validDataProvider()
    {
        $result = array(
            array(
                array(
                    'description' => Random::str(128),
                    'quantity' => Random::float(0.0001, 99.99),
                    'amount' => new MonetaryAmount(Random::int(1, 1000)),
                    'vat_code' => Random::int(1, 6),
                    'measure' => null,
                    'excise' => null,
                    'payment_mode' => null,
                    'payment_subject' => null,
                    'product_code' => null,
                    'mark_code_info' => null,
                    'mark_mode' => null,
                    'mark_quantity' => null,
                    'supplier' => new Supplier(array(
                        'name' => Random::str(128),
                        'phone' => Random::str(4, 12, '1234567890'),
                        'inn' => '1000000000'
                    )),
                    'agent_type' => null,
                    'payment_subject_industry_details' => null,
                )
            )
        );

        for ($i = 0; $i < 9; $i++) {
            $test = array(
                array(
                    'description' => Random::str(128),
                    'quantity' => Random::float(0.0001, 99.99),
                    'measure' => Random::value(ReceiptItemMeasure::getValidValues()),
                    'amount' => array(
                        'value' => round(Random::float(0.1, 99.99), 2),
                        'currency' => Random::value(CurrencyCode::getValidValues())
                    ),
                    'vat_code' => Random::int(1, 6),
                    'excise' => round(Random::float(1.0, 10.0), 2),
                    'payment_mode' => Random::value(PaymentMode::getValidValues()),
                    'payment_subject' => Random::value(PaymentSubject::getValidValues()),
                    'product_code' => Random::value(array(
                        null,
                        Random::str(2, 96, '0123456789ABCDEF '),
                        new ProductCode('010463003407001221SxMGorvNuq6Wk91fgr92sdfsdfghfgjh'),
                    )),
                    'country_of_origin_code' => Random::value(array('RU', 'US', 'CN')),
                    'customs_declaration_number' => Random::value(array(
                            null,
                            '',
                            Random::str(1),
                            Random::str(2, 31),
                            Random::str(32),
                    )),
                    'mark_code_info' => array(
                        'mark_code_raw' => '010460406000590021N4N57RTCBUZTQ\u001d2403054002410161218\u001d1424010191ffd0\u001g92tIAF/YVpU4roQS3M/m4z78yFq0nc/WsSmLeX6QkF/YVWwy5IMYAeiQ91Xa2m/fFSJcOkb2N+uUUtfr4n0mOX0Q==',
                    ),
                    'mark_mode' => Random::value(array(null, 0, 1, '1',)),
                    'payment_subject_industry_details' => array(
                        array(
                            'federal_id' => '001',
                            'document_date' => date('Y-m-d', Random::int(100000000, 200000000)),
                            'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                            'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
                        )
                    ),
                    'supplier' => array(
                        'name' => Random::str(128),
                        'phone' => Random::str(4, 12, '1234567890'),
                        'inn' => '1000000000'
                    ),
                    'agent_type' => Random::value(AgentType::getValidValues()),
                )
            );
            if ($test[0]['measure'] === ReceiptItemMeasure::PIECE) {
                $test[0]['mark_quantity'] = array(
                    'numerator' => Random::int(1, 100),
                    'denominator' => 100,
                );
            }
            $result[] = $test;
        }
        return $result;
    }

    public function invalidDescriptionDataProvider()
    {
        return array(
            array(''),
            array(new Airline()),
            array(new ProductCode())
        );
    }

    public function invalidQuantityDataProvider()
    {
        return array(
            array(null),
            array('test'),
            array(0.0)
        );
    }

    public function invalidMeasureDataProvider()
    {
        return array(
            array(array()),
            array(true),
            array(new \stdClass()),
            array(Random::str(10)),
        );
    }

    public function invalidVatCodeDataProvider()
    {
        return array(
            array('test'),
            array(0.0)
        );
    }

    public function invalidExciseDataProvider()
    {
        return array(
            array('test'),
            array(new Airline()),
            array(-Random::float(10)),
        );
    }

    public function invalidProductCodeDataProvider()
    {
        return array(
            array(new StringObject('')),
            array(true),
            array(false),
            array(new \stdClass()),
            array(array()),
            array(Random::str(2, 96, 'GHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=`~?><:"\'')),
            array(Random::str(97, 100, '0123456789ABCDEF ')),
        );
    }

    public function invalidPaymentDataProvider()
    {
        return array(
            array(new Airline())
        );
    }

    public function invalidSupplierDataProvider()
    {
        return array(
            array(null),
            array(new Airline())
        );
    }

    public function invalidCountryOfOriginCodeDataProvider()
    {
        return array(
            array(new Airline()),
            array(new ProductCode()),
            array(Random::str(2, 2, '0123456789!@#$%^&*()_+-=')),
            array(Random::str(3, 10)),
        );
    }

    public function invalidAgentTypeDataProvider()
    {
        return array(
            array(new Airline()),
            array(Random::str(1, 10)),
        );
    }

    public function invalidMarkModeDataProvider()
    {
        return array(
            array(array()),
            array(new Airline()),
            array(true),
        );
    }

    public function invalidMarkQuantityDataProvider()
    {
        return array(
            array(1.0),
            array(1),
            array(true),
            array(new \stdClass()),
        );
    }

    public function invalidMarkCodeInfoDataProvider()
    {
        return array(
            array(1.0),
            array(1),
            array(true),
            array(new \stdClass()),
            array(Random::str(1, 10)),
        );
    }

    public function invalidCustomsDeclarationNumberDataProvider()
    {
        return array(
            array(true),
            array(false),
            array(new \stdClass()),
            array(array()),
            array(Random::str(33, 64)),
        );
    }

    public function invalidPaymentSubjectIndustryDetailsDataProvider()
    {
        return array(
            array(1.0),
            array(1),
            array(true),
            array(new \stdClass()),
            array(array(new \stdClass())),
        );
    }
}
