<?php

namespace Harvest\Forecast\HttpClient\Plugin;

use Harvest\Forecast\Exception\InvalidTokenException;
use Harvest\Forecast\HttpClient\Message\ResponseMediator;
use Harvest\Forecast\Exception\MissingTokenException;
use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * Class ForecastExceptionThrower
 * @package Harvest\Forecast\HttpClient\Plugin
 */
class ForecastExceptionThrower implements Plugin
{
    use Plugin\VersionBridgePlugin;

    /**
     * {@inheritdoc}
     */
    public function doHandleRequest(RequestInterface $request, callable $next, callable $first)
    {
        return $next($request)->then(function (ResponseInterface $response) use ($request) {
            if ($response->getStatusCode() < 400 || $response->getStatusCode() > 600) {
                return $response;
            }

            $content = ResponseMediator::getContent($response);
            if (is_array($content) && isset($content['reason'])) {
                if ($content['reason'] === 'missing-token' && $response->getStatusCode() === 401) {
                    throw new MissingTokenException($content['reason'], 400);
                }

                if ($content['reason'] === 'invalid-token' && $response->getStatusCode() === 401) {
                    throw new InvalidTokenException($content['reason'], 400);
                }
            }

            throw new RuntimeException($content['reason'] ?? $content, $response->getStatusCode());
        });
    }
}
