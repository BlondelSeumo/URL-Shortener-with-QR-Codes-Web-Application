<?php

namespace Tests\YooKassa\Request\Payouts;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payout\SbpParticipantBank;
use YooKassa\Request\Payouts\SbpBanksResponse;

class SbpBanksResponseTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetItems($options)
    {
        $instance = new SbpBanksResponse($options);

        self::assertEquals($options['type'], $instance->getType());
        self::assertEquals(count($options['items']), count($instance->getItems()));

        foreach ($instance->getItems() as $index => $item) {
            self::assertTrue($item instanceof SbpParticipantBank);
            self::assertArrayHasKey($index, $options['items']);
            self::assertEquals($options['items'][$index]['bank_id'], $item->getBankId());
            self::assertEquals($options['items'][$index]['name'], $item->getName());
            self::assertEquals($options['items'][$index]['bic'], $item->getBic());
        }
    }

    public function validDataProvider()
    {
        return array(
            array(
                array(
                    'type' => 'list',
                    'items' => array(),
                ),
            ),
            array(
                array(
                    'type' => 'list',
                    'items' => array(
                        array(
                            'bank_id' => Random::str(36),
                            'name' => Random::str(36),
                            'bic' => Random::str(36),
                        ),
                    ),
                ),
            ),
            array(
                array(
                    'type' => 'list',
                    'items' => array(
                        array(
                            'bank_id' => Random::str(36),
                            'name' => Random::str(36),
                            'bic' => Random::str(36),
                        ),
                        array(
                            'bank_id' => Random::str(36),
                            'name' => Random::str(36),
                            'bic' => Random::str(36),
                        ),
                    ),
                ),
            ),
            array(
                array(
                    'type' => 'list',
                    'items' => array(
                        array(
                            'bank_id' => Random::str(36),
                            'name' => Random::str(36),
                            'bic' => Random::str(36),
                        ),
                        array(
                            'bank_id' => Random::str(36),
                            'name' => Random::str(36),
                            'bic' => Random::str(36),
                        ),
                        array(
                            'bank_id' => Random::str(36),
                            'name' => Random::str(36),
                            'bic' => Random::str(36),
                        ),
                    ),
                ),
            ),
        );
    }

}
