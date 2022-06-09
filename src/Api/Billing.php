<?php

declare(strict_types=1);

namespace Harvest\Forecast\Api;

final class Billing extends AbstractApi
{
    /**
     * Get subscription information
     *
     * @return array|string
     */
    public function getSubscription()
    {
        return $this->get('/billing/subscription');
    }
}
