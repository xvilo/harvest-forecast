<?php

declare(strict_types=1);

namespace Harvest\Forecast\Api;

final class Accounts extends AbstractApi
{
    public function getById(int $id)
    {
        return $this->get(
            sprintf('accounts/%d', $id)
        );
    }
}
