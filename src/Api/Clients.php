<?php

declare(strict_types=1);

namespace Harvest\Forecast\Api;

final class Clients extends AbstractApi
{
    public function getAll()
    {
        return $this->get('/clients');
    }
}
