<?php

declare(strict_types=1);

namespace Harvest\Forecast\Api;

final class People extends AbstractApi
{
    public function getAll()
    {
        return $this->get('people');
    }

    public function getById(int $id)
    {
        return $this->get(
            sprintf('people/%d', $id)
        );
    }
}
