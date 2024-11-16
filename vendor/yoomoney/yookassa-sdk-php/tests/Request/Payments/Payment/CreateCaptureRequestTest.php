<?php

namespace Tests\YooKassa\Request\Payments\Payment;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt;
use YooKassa\Model\ReceiptItem;
use YooKassa\Request\Payments\Payment\CreateCaptureRequest;
use YooKassa\Request\Payments\Payment\CreateCaptureRequestBuilder;

class CreateCaptureRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testRecipient($options)
    {
        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasAmount());
        self::assertNull($instance->getAmount());
        self::assertNull($instance->amount);

        $instance->setAmount($options['amount']);
        self::assertTrue($instance->hasAmount());
        self::assertEquals($options['amount'], $instance->getAmount());
        self::assertEquals($options['amount'], $instance->amount);

        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasAmount());
        self::assertNull($instance->getAmount());
        self::assertNull($instance->amount);

        $instance->amount = $options['amount'];
        self::assertTrue($instance->hasAmount());
        self::assertEquals($options['amount'], $instance->getAmount());
        self::assertEquals($options['amount'], $instance->amount);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testDeal($options)
    {
        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasDeal());
        self::assertNull($instance->getDeal());
        self::assertNull($instance->deal);

        $instance->setDeal($options['deal']);
        if ($instance->hasDeal()) {
            self::assertTrue($instance->hasDeal());
            self::assertSame($options['deal'], $instance->getDeal()->toArray());
            self::assertSame($options['deal'], $instance->deal->toArray());
        } else {
            self::assertFalse($instance->hasDeal());
            self::assertSame($options['deal'], $instance->getDeal());
            self::assertSame($options['deal'], $instance->deal);
        }

        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasDeal());
        self::assertNull($instance->getDeal());
        self::assertNull($instance->deal);

        $instance->deal = $options['deal'];
        if ($instance->hasDeal()) {
            self::assertTrue($instance->hasDeal());
            self::assertSame($options['deal'], $instance->getDeal()->toArray());
            self::assertSame($options['deal'], $instance->deal->toArray());
        } else {
            self::assertFalse($instance->hasDeal());
            self::assertSame($options['deal'], $instance->getDeal());
            self::assertSame($options['deal'], $instance->deal);
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testAirline($options)
    {
        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasAirline());
        self::assertNull($instance->getAirline());
        self::assertNull($instance->airline);

        $instance->setAirline($options['airline']);
        if (is_array($options['airline'])) {
            self::assertTrue($instance->hasAirline());
            self::assertSame($options['airline'], $instance->getAirline()->toArray());
            self::assertSame($options['airline'], $instance->airline->toArray());
        } else {
            self::assertFalse($instance->hasAirline());
            self::assertSame($options['airline'], $instance->getAirline());
            self::assertSame($options['airline'], $instance->airline);
        }

        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasAirline());
        self::assertNull($instance->getAirline());
        self::assertNull($instance->airline);

        $instance->airline = $options['airline'];
        if (is_array($options['airline'])) {
            self::assertTrue($instance->hasAirline());
            self::assertSame($options['airline'], $instance->getAirline()->toArray());
            self::assertSame($options['airline'], $instance->airline->toArray());
        } else {
            self::assertFalse($instance->hasAirline());
            self::assertSame($options['airline'], $instance->getAirline());
            self::assertSame($options['airline'], $instance->airline);
        }
    }

    public function testValidate()
    {
        $instance = new CreateCaptureRequest();

        self::assertTrue($instance->validate());
        $amount = new MonetaryAmount();
        $instance->setAmount($amount);
        self::assertFalse($instance->validate());
        $amount->setValue(1);
        self::assertTrue($instance->validate());

        $receipt = new Receipt();
        $instance->setReceipt($receipt);
        $item = new ReceiptItem();
        $item->setPrice(new MonetaryAmount(10));
        $item->setDescription('test');
        $receipt->addItem($item);
        self::assertFalse($instance->validate());
        $receipt->getCustomer()->setPhone('123123');
        self::assertTrue($instance->validate());
        $item->setVatCode(3);
        self::assertTrue($instance->validate());
        $receipt->setTaxSystemCode(4);
        self::assertTrue($instance->validate());

        self::assertNotNull($instance->getReceipt());
        $instance->removeReceipt();
        self::assertTrue($instance->validate());
        self::assertNull($instance->getReceipt());

        $instance->setAmount(new MonetaryAmount());
        self::assertFalse($instance->validate());
    }

    public function testBuilder()
    {
        $builder = CreateCaptureRequest::builder();
        self::assertTrue($builder instanceof CreateCaptureRequestBuilder);
    }

    public function validDataProvider()
    {
        $result = array();
        $currencies = CurrencyCode::getValidValues();
        for ($i = 0; $i < 10; $i++) {
            $request = array(
                'amount' => new MonetaryAmount(mt_rand(1, 1000000), $currencies[mt_rand(0, count($currencies) - 1)]),
                'deal' => $i % 2 ? array(
                    'settlements' => array()
                ) : null,
                'airline' => Random::bool() ? array(
                    'booking_reference' => Random::str(3, 10),
                    'ticket_number'     => Random::str(10, '0123456789'),
                    'passengers'        => array(
                        array(
                            'first_name' => Random::str(3, 10),
                            'last_name'  => Random::str(3, 10),
                        ),
                    ),
                    'legs'              => array(
                        array(
                            'departure_airport'   => Random::str(3, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),
                            'destination_airport' => Random::str(3, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),
                            'departure_date'      => date('Y-m-d', Random::int(11111111, time())),
                        ),
                    ),
                ) : null
            );
            $result[] = array($request);
        }
        return $result;
    }
}
