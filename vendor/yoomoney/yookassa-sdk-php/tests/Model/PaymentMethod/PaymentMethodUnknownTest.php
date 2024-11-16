<?php

namespace Tests\YooKassa\Model\PaymentMethod;

use YooKassa\Model\PaymentMethod\AbstractPaymentMethod;
use YooKassa\Model\PaymentMethod\PaymentMethodUnknown;
use YooKassa\Model\PaymentMethodType;

class PaymentMethodUnknownTest extends AbstractPaymentMethodTest
{
    /**
     * @return AbstractPaymentMethod
     */
    protected function getTestInstance()
    {
        return new PaymentMethodUnknown();
    }

    /**
     * @return string
     */
    protected function getExpectedType()
    {
        return PaymentMethodType::UNKNOWN;
    }
}
