<?php

namespace Tests\YooKassa\Model\PaymentData;

use YooKassa\Model\PaymentData\AbstractPaymentData;
use YooKassa\Model\PaymentData\PaymentDataSberLoan;
use YooKassa\Model\PaymentMethodType;

class PaymentDataSberLoanTest extends AbstractPaymentDataTest
{
    /**
     * @return AbstractPaymentData
     */
    protected function getTestInstance()
    {
        return new PaymentDataSberLoan();
    }

    /**
     * @return string
     */
    protected function getExpectedType()
    {
        return PaymentMethodType::SBER_LOAN;
    }
}
