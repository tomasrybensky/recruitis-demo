<?php

namespace App\Integrations\Recruitis;

use App\Data\JobsResponse;
use App\Data\Meta;
use App\Services\JobService;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

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

    public function createDtoFromResponse(Response $response): JobsResponse
    {
        $data = $response->json();
        $jobResponse = new JobsResponse();
        $jobResponse->jobs = [];

        foreach ($data['payload'] as $jobData) {
            $jobResponse->jobs[] = JobService::fillJobDataObject($jobData);
        }
        $jobResponse->meta = $this->fillMetaObject($data['meta']);

        return $jobResponse;
    }

    protected function defaultQuery(): array
    {
        return [
            'activity_state' => 1,
            'access_state' => 1,
            'page' => $this->page,
        ];
    }

    private function fillMetaObject(array $data): Meta
    {
        $meta = new Meta();
        $meta->entriesFrom = $data['entries_from'];
        $meta->entriesTo = $data['entries_to'];
        $meta->entriesTotal = $data['entries_total'];

        return $meta;
    }
}
