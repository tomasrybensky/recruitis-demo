<?php

namespace App\Integrations\Recruitis;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RecruitisConnector extends Connector
{
    public function __construct(private readonly ParameterBagInterface $params)
    {
    }

    public function resolveBaseUrl(): string
    {
        return 'https://app.recruitis.io/api2/';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultAuth(): ?Authenticator
    {
        return new TokenAuthenticator($this->params->get('recruitis_api_token'));
    }
}