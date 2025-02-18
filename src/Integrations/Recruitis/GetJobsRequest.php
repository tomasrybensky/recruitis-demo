<?php

namespace App\Integrations\Recruitis;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetJobsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(public int $page)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/jobs';
    }

    protected function defaultQuery(): array
    {
        return [
            'activity_state' => 1,
            'access_state' => 1,
            'page' => $this->page,
        ];
    }
}