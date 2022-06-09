<?php

declare(strict_types=1);

namespace Harvest\Forecast\Api;

use DateTimeInterface;
use Harvest\Forecast\Client;

final class Milestones extends AbstractApi
{
    public function getByStartAndEndDate(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->get('/milestones', [
            'start_date' => $startDate->format(Client::DATE_FORMAT),
            'end_date' => $endDate->format(Client::DATE_FORMAT),
        ]);
    }
}
