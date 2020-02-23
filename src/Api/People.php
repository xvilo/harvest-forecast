<?php
declare(strict_types=1);

namespace Harvest\Forecast\Api;

class People extends AbstractApi
{
    public function getAll()
    {
        return $this->get('people');
    }
}
