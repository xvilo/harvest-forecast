<?php

declare(strict_types=1);

namespace Harvest\Forecast\Api;

final class User extends AbstractApi
{
    public function whoAmI()
    {
        return $this->get('/whoami');
    }

    public function getConnections()
    {
        return $this->get('/user_connections');
    }

    public function getRoles()
    {
        return $this->get('/roles');
    }

    public function getFtuxState()
    {
        return $this->get('/ftux_state');
    }
}
