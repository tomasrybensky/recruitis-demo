<?php

namespace App\Integrations\Recruitis;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetJobDetailRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(public int $id)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/jobs/' . $this->id;
    }

    protected function defaultQuery(): array
    {
        return [
            'activity_state' => 1,
            'access_state' => 1,
        ];
    }
}