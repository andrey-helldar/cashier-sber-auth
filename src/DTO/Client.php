<?php

namespace Helldar\CashierDriver\SberAuth\DTO;

use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\HttpBuilder as HttpBuilderHelper;
use Helldar\Support\Facades\Helpers\Instance;
use Helldar\Support\Helpers\HttpBuilder;

class Client
{
    use Makeable;

    /** @var \Helldar\Support\Helpers\HttpBuilder */
    protected $http;

    protected $client_id;

    protected $client_secret;

    protected $member_id;

    protected $payment_id;

    protected $unique_id;

    protected $scope;

    protected $grant_type = 'client_credentials';

    protected $uri = 'ru/prod/tokens/v2/oauth';

    public function host($host): Client
    {
        $this->http = Instance::of($host, HttpBuilder::class) ? $host : HttpBuilderHelper::parse($host);

        return $this;
    }

    public function clientId($client_id): Client
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function clientSecret($client_secret): Client
    {
        $this->client_secret = $client_secret;

        return $this;
    }

    public function memberId($member_id): Client
    {
        $this->member_id = $member_id;

        return $this;
    }

    public function paymentId($payment_id): Client
    {
        $this->payment_id = $payment_id;

        return $this;
    }

    public function uniqueId($unique_id): Client
    {
        $this->unique_id = $unique_id;

        return $this;
    }

    public function getUniqueId(): string
    {
        return $this->unique_id;
    }

    public function scope($scope): Client
    {
        $this->scope = $scope;

        return $this;
    }

    public function url(): string
    {
        return $this->http->setPath($this->uri)->compile();
    }

    public function headers(): array
    {
        return [
            'grant_type' => $this->grant_type,
            'scope'      => $this->scope,
        ];
    }

    public function data(): array
    {
        return [
            'Accept'          => 'application/json',
            'Content-Type'    => 'application/x-www-form-urlencoded',
            'Authorization'   => 'Basic ' . $this->authorization(),
            'X-IBM-Client-Id' => $this->getClientId(),
            'RqUID'           => $this->rqUID(),
        ];
    }

    protected function authorization(): string
    {
        $client = $this->client_id;
        $secret = $this->client_secret;

        return base64_encode($client . ':' . $secret);
    }

    protected function rqUID(): string
    {
        return $this->unique_id;
    }
}
