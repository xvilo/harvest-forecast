<?php

declare(strict_types=1);

namespace Harvest\Forecast;

use Harvest\Forecast\HttpClient\Builder;
use Harvest\Forecast\HttpClient\Plugin\Authentication;
use Harvest\Forecast\HttpClient\Plugin\ForecastExceptionThrower;
use Harvest\Forecast\HttpClient\Plugin\History;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\HttpClient;
use Http\Discovery\UriFactoryDiscovery;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class Client
 * @author Sem Schilder <me@sem.design>
 */
class Client
{
    public const DATE_FORMAT = 'Y-m-d';

    /**
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * @var History
     */
    private $responseHistory;

    /**
     * @var Api\User
     */
    public $user;
    /**
     * @var Api\Clients
     */
    public $clients;

    /**
     * @var Api\People
     */
    public $people;
    /**
     * @var Api\Projects
     */
    public $projects;

    /**
     * @var Api\Assignments
     */
    public $assignments;

    /**
     * @var Api\Milestones
     */
    public $milestones;

    /** @var Api\Billing */
    public $billing;

    /** @var Api\Accounts */
    public $accounts;

    /**
     * @param Builder|null $httpClientBuilder
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        // Setup Http client
        $this->responseHistory = new History();
        $this->httpClientBuilder = $httpClientBuilder ?: new Builder();

        $this->setupHttpBuilder();

        // Setup api
        $this->user = new Api\User($this);
        $this->clients = new Api\Clients($this);
        $this->people = new Api\People($this);
        $this->projects = new Api\Projects($this);
        $this->assignments = new Api\Assignments($this);
        $this->milestones = new Api\Milestones($this);
        $this->billing = new Api\Billing($this);
        $this->accounts = new Api\Accounts($this);
    }

    private function setupHttpBuilder(): void
    {
        $this->httpClientBuilder->addPlugin(new Plugin\HistoryPlugin($this->responseHistory));
        $this->httpClientBuilder->addPlugin(new Plugin\RedirectPlugin());
        $this->httpClientBuilder->addPlugin(new Plugin\AddHostPlugin(UriFactoryDiscovery::find()->createUri('https://api.forecastapp.com')));
        $this->httpClientBuilder->addPlugin(new ForecastExceptionThrower());
    }

    /**
     * Create a Harvest\Forecast\Client using a HttpClient.
     *
     * @param HttpClient $httpClient
     *
     * @return Client
     */
    public static function createWithHttpClient(HttpClient $httpClient)
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @param string $token
     * @param int $accountId
     */
    public function authenticate(string $token, int $accountId)
    {
        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($token, $accountId));
    }

    /**
     * Add a cache plugin to cache responses locally.
     *
     * @param CacheItemPoolInterface $cachePool
     * @param array                  $config
     */
    public function addCache(CacheItemPoolInterface $cachePool, array $config = [])
    {
        $this->getHttpClientBuilder()->addCache($cachePool, $config);
    }

    /**
     * Remove the cache plugin.
     */
    public function removeCache()
    {
        $this->getHttpClientBuilder()->removeCache();
    }

    /**
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function getLastResponse()
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * @return HttpMethodsClient
     */
    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * @return Builder
     */
    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }
}
