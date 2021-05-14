<?php

declare(strict_types=1);

namespace Harvest\Forecast\Api;

final class Projects extends AbstractApi
{
    public function getAll()
    {
        return $this->get('projects');
    }
}
