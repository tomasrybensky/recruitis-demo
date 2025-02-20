<?php

namespace App\Integrations\Recruitis;

use App\Data\Job;
use App\Services\JobService;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetJobDetailRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(public int $id)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/jobs/'.$this->id;
    }

    protected function defaultQuery(): array
    {
        return [
            'activity_state' => 1,
            'access_state' => 1,
        ];
    }

    public function createDtoFromResponse(Response $response): Job
    {
        $data = $response->json();

        return JobService::fillJobDataObject($data['payload']);
    }
}
