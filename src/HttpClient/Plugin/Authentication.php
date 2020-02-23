<?php

namespace Harvest\Forecast\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;

class Authentication implements Plugin
{
    use Plugin\VersionBridgePlugin;

    private $token;
    private $accountId;

    public function __construct(string $token, int $accountId)
    {
        $this->token = $token;
        $this->accountId = $accountId;
    }

    /**
     * {@inheritdoc}
     */
    public function doHandleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $request = $request->withHeader('Authorization', sprintf('Bearer %s', $this->token));
        $request = $request->withHeader('Forecast-Account-ID', $this->accountId);

        return $next($request);
    }
}
