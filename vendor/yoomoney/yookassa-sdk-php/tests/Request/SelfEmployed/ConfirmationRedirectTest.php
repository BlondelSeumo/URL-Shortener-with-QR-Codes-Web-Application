<?php

namespace Tests\YooKassa\Request\SelfEmployed;

use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationRedirect;

class ConfirmationRedirectTest extends AbstractConfirmationTest
{
    /**
     * @return SelfEmployedRequestConfirmationRedirect
     */
    protected function getTestInstance()
    {
        return new SelfEmployedRequestConfirmationRedirect();
    }

    /**
     * @return string
     */
    protected function getExpectedType()
    {
        return SelfEmployedConfirmationType::REDIRECT;
    }
}
