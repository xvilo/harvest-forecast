<?php
declare(strict_types=1);

namespace Harvest\Forecast\Api;

class User extends AbstractApi
{
    public function whoAmI()
    {
        return $this->get('whoami');
    }
}
