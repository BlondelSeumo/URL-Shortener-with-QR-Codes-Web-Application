<?php

namespace Tests\YooKassa\Request\Receipts;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\ProductCode;
use YooKassa\Helpers\Random;
use YooKassa\Model\Airline;
use YooKassa\Model\Receipt\AdditionalUserProps;
use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\OperationalDetails;
use YooKassa\Model\Receipt\ReceiptItemMeasure;
use YooKassa\Model\Receipt\SettlementType;
use YooKassa\Model\ReceiptType;
use YooKassa\Request\Receipts\ReceiptResponseInterface;
use YooKassa\Request\Receipts\ReceiptResponseItem;
use YooKassa\Request\Receipts\ReceiptResponseItemInterface;

abstract class AbstractReceiptResponseTest extends TestCase
{
    protected $type;

    protected $valid = true;

    /**
     * @param $options
     * @return ReceiptResponseInterface
     */
    abstract protected function getTestInstance($options);

    /**
     * @param $options
     * @return array
     */
    abstract protected function addSpecificProperties($options, $i);

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    abstract public function testSpecificProperties($options);

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetId($options)
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['id'], $instance->getId());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetType($options)
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['type'], $instance->getType());
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetStatus($options)
    {
        $instance = $this->getTestInstance($options);
        if (empty($options['status'])) {
            self::assertNull($instance->getStatus());
        } else {
            self::assertEquals($options['status'], $instance->getStatus());
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetTaxSystemCode($options)
    {
        $instance = $this->getTestInstance($options);
        if (empty($options['tax_system_code'])) {
            self::assertNull($instance->getTaxSystemCode());
        } else {
            self::assertEquals($options['tax_system_code'], $instance->getTaxSystemCode());
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetReceiptOperationalDetails($options)
    {
        $instance = $this->getTestInstance($options);
        if (empty($options['receipt_operational_details'])) {
            self::assertNull($instance->getReceiptOperationalDetails());
        } else {
            self::assertEquals($options['receipt_operational_details'], $instance->getReceiptOperationalDetails()->toArray());
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testReceiptIndustryDetails($options)
    {
        $instance = $this->getTestInstance($options);

        self::assertCount(count($options['receipt_industry_details']), $instance->getReceiptIndustryDetails());

        foreach ($instance->getReceiptIndustryDetails() as $index => $item) { /** @var IndustryDetails $item */
            self::assertTrue($item instanceof IndustryDetails);
            self::assertArrayHasKey($index, $options['receipt_industry_details']);
            self::assertEquals($options['receipt_industry_details'][$index]['federal_id'], $item->getFederalId());
            self::assertEquals($options['receipt_industry_details'][$index]['document_date'], $item->getDocumentDate()->format(IndustryDetails::DOCUMENT_DATE_FORMAT));
            self::assertEquals($options['receipt_industry_details'][$index]['document_number'], $item->getDocumentNumber());
            self::assertEquals($options['receipt_industry_details'][$index]['value'], $item->getValue());
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetItems($options)
    {
        $instance = $this->getTestInstance($options);

        self::assertEquals(count($options['items']), count($instance->getItems()));

        foreach ($instance->getItems() as $index => $item) {
            self::assertTrue($item instanceof ReceiptResponseItemInterface);
            self::assertArrayHasKey($index, $options['items']);
            self::assertEquals($options['items'][$index]['description'], $item->getDescription());
            self::assertEquals($options['items'][$index]['amount']['value'], $item->getPrice()->getValue());
            self::assertEquals($options['items'][$index]['amount']['currency'], $item->getPrice()->getCurrency());
            self::assertEquals($options['items'][$index]['quantity'], $item->getQuantity());
            self::assertEquals($options['items'][$index]['vat_code'], $item->getVatCode());
        }
    }

    public function validDataProvider()
    {
        $this->valid = true;
        $receipts = array();
        for ($i = 0; $i < 10; $i++) {
            $receipts[] = $this->generateReceipts($this->type, true);
        }
        return $receipts;
    }

    public function invalidDataProvider()
    {
        $this->valid = false;
        $receipts = array();
        for ($i = 0; $i < 10; $i++) {
            $receipts[] = $this->generateReceipts($this->type, false);
        }
        return $receipts;
    }

    private function generateReceipts($type, $valid)
    {
        $this->valid = $valid;
        $return = array();
        $count = Random::int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $return[] = $this->generateReceipt($type, $i);
        }

        return $return;
    }

    private function generateReceipt($type, $index)
    {
        $receipt = array(
            'id' => Random::str(39),
            'type' => $type,
            'status' => Random::value(array('pending', 'succeeded', 'canceled', null)),
            'fiscal_document_number' => Random::int(4),
            'fiscal_storage_number' => Random::int(16),
            'fiscal_attribute' => Random::int(10),
            'registered_at' => date(YOOKASSA_DATE, Random::int(1000000000, time())),
            'fiscal_provider_id' => Random::str(36),
            'items' => $this->generateItems(),
            'settlements' => $this->generateSettlements(),
            'tax_system_code' => Random::int(1, 6),
            'receipt_industry_details' => array(
                array(
                    'federal_id' => Random::str(1, 255),
                    'document_date' => date(IndustryDetails::DOCUMENT_DATE_FORMAT),
                    'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                    'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
                ),
            ),
            'receipt_operational_details' => array(
                'operation_id' => Random::str(1, OperationalDetails::OPERATION_ID_MAX_LENGTH),
                'value' => Random::str(1, OperationalDetails::VALUE_MAX_LENGTH),
                'created_at' => date(YOOKASSA_DATE, Random::int(1000000000, time())),
            ),
            'on_behalf_of' => Random::int(6)
        );

        return $this->addSpecificProperties($receipt, $index);
    }

    private function generateItems()
    {
        $return = array();
        $count = Random::int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $return[] = $this->generateItem();
        }

        return $return;
    }

    private function generateItem()
    {
        $item = array(
            'description' => Random::str(1, 128),
            'amount' => array(
                'value' => round(Random::float(1.00, 100.00), 2),
                'currency' => 'RUB',
            ),
            'quantity' => round(Random::float(0.001, 99.999), 3),
            'measure' => Random::value(ReceiptItemMeasure::getValidValues()),
            'vat_code' => Random::int(1, 6),
            'country_of_origin_code' => Random::value(array('RU', 'US', 'CN')),
            'customs_declaration_number' => Random::str(1, 32),
            'mark_code_info' => array(
                'mark_code_raw' => '010460406000590021N4N57RTCBUZTQ\u001d2403054002410161218\u001d1424010191ffd0\u001g92tIAF/YVpU4roQS3M/m4z78yFq0nc/WsSmLeX6QkF/YVWwy5IMYAeiQ91Xa2m/fFSJcOkb2N+uUUtfr4n0mOX0Q==',
            ),
            'mark_mode' => 0,
            'payment_subject_industry_details' => array(
                array(
                    'federal_id' => '001',
                    'document_date' => date('Y-m-d', Random::int(100000000, 200000000)),
                    'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                    'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
                )
            ),
        );
        if ($item['measure'] === ReceiptItemMeasure::PIECE) {
            $item['mark_quantity'] = array(
                'numerator' => Random::int(1, 100),
                'denominator' => 100,
            );
        }
        return $item;
    }

    private function generateSettlements()
    {
        $return = array();
        $count = Random::int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $return[] = $this->generateSettlement();
        }

        return $return;
    }

    private function generateSettlement()
    {
        return array(
            'description' => Random::str(1, 128),
            'amount' => array(
                'value' => round(Random::float(1.00, 100.00), 2),
                'currency' => 'RUB',
            ),
            'quantity' => round(Random::float(0.001, 99.999), 3),
            'vat_code' => Random::int(1, 6),
        );
    }

    public function invalidAllDataProvider()
    {
        return array(
            array(array(new ProductCode())),
            array(array(new Airline())),
            array(SettlementType::PREPAYMENT),
            array(0),
            array('test'),
            array(10)
        );
    }

    public function invalidBoolDataProvider()
    {
        return array(
            array(true),
            array(false)
        );
    }

    public function invalidBoolNullDataProvider()
    {
        return array(
            array(true),
            array(false),
            array(null)
        );
    }

    public function invalidItemsSettlementsDataProvider()
    {
        return array(
            array(
                array(
                    'id' => Random::str(39),
                    'type' => Random::value(ReceiptType::getEnabledValues()),
                    'status' => null,
                    'items' => null,
                    'settlements' => null
                )
            ),
            array(
                array(
                    'id' => Random::str(39),
                    'type' => Random::value(ReceiptType::getEnabledValues()),
                    'status' => null,
                    'items' => 1,
                    'settlements' => 1
                )
            ),
            array(
                array(
                    'id' => Random::str(39),
                    'type' => Random::value(ReceiptType::getEnabledValues()),
                    'status' => null,
                    'items' => array(new Airline()),
                    'settlements' => array(new Airline())
                )
            ),
            array(null),
            array(new Airline())
        );
    }

    public function invalidFromArray()
    {
        return array(
            array(
                array(
                    'id' => Random::str(39),
                    'type' => Random::value(ReceiptType::getEnabledValues()),
                    'status' => null,
                    'items' => false
                )
            ),
            array(
                array(
                    'id' => Random::str(39),
                    'type' => Random::value(ReceiptType::getEnabledValues()),
                    'status' => null,
                    'items' => 1
                )
            ),
            array(
                array(
                    'id' => Random::str(39),
                    'type' => Random::value(ReceiptType::getValidValues()),
                    'status' => null,
                    'items' => array(new ReceiptResponseItem()),
                    'settlements' => 1
                )
            )
        );
    }
}
