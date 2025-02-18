<?php

namespace App\Services;

use App\Data\Job;
use App\Integrations\Recruitis\GetJobsRequest;
use App\Integrations\Recruitis\RecruitisConnector;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use App\Request\GetJobsRequest as GetJobsRequestData;

class JobService
{
    public function __construct(
        private RecruitisConnector  $connector,
        private SerializerInterface $serializer,
        private CacheInterface      $cache
    ) {
    }

    public function getJobs(GetJobsRequestData $requestData): array
    {
        return $this->cache->get('jobs-' . $requestData->page, function (ItemInterface $item) use ($requestData) {
            $item->expiresAfter(300); // Cache for 5 minutes

            $request = new GetJobsRequest($requestData->page);
            $response = $this->connector->send($request);

            if ($response->successful()) {
                $data = json_decode($response->body(), true);

                foreach ($data['payload'] as &$job) {
                    // Remove all unnecessary fields and possible sensitive data
                    $jobDataObject = $this->serializer
                        ->denormalize($job, Job::class, 'json');

                    $job = $this->serializer->normalize($jobDataObject, 'json');
                }

                return $data;
            }

            return [];
        });
    }
}
