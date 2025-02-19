<?php

namespace App\Services;

use App\Data\JobApplication;
use App\Data\Job;
use App\Integrations\Recruitis\ApplyForJobRequest;
use App\Integrations\Recruitis\GetJobDetailRequest;
use App\Integrations\Recruitis\GetJobsRequest;
use App\Integrations\Recruitis\RecruitisConnector;
use App\Request\GetJobsRequest as GetJobsRequestData;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class JobService
{
    public function __construct(
        private RecruitisConnector $connector,
        private SerializerInterface $serializer,
        private CacheInterface $cache,
    ) {
    }

    public function getJobs(GetJobsRequestData $requestData): array
    {
        return $this->cache->get('jobs-'.$requestData->page, function (ItemInterface $item) use ($requestData) {
            $request = new GetJobsRequest($requestData->page);
            $response = $this->connector->send($request);

            if ($response->successful()) {
                $data = json_decode($response->body(), true);

                foreach ($data['payload'] as &$job) {
                    $jobDataObject = $this->fillJobDataObject($job);

                    $job = $this->serializer->normalize($jobDataObject, 'json');
                }

                $item->expiresAfter(300);

                return $data;
            }

            return [];
        });
    }

    public function getJob(int $id): array
    {
        return $this->cache->get('jobs-'.$id, function (ItemInterface $item) use ($id) {
            $request = new GetJobDetailRequest($id);
            $response = $this->connector->send($request);

            if ($response->successful()) {
                $data = json_decode($response->body(), true);

                $jobDataObject = $this->fillJobDataObject($data['payload']);

                $item->expiresAfter(300);

                return $this->serializer->normalize($jobDataObject, 'json');
            }

            return [];
        });
    }

    public function applyForJob(JobApplication $data): bool
    {
        $request = new ApplyForJobRequest($data);
        $response = $this->connector->send($request);

        if ($response->successful()) {
            return true;
        }

        return false;
    }

    private function fillJobDataObject(array $data): Job
    {
        $jobDataObject = $this->serializer
            ->denormalize($data, Job::class, 'json');

        $jobDataObject->salaryMin = $data['salary']['min'] ?? null;
        $jobDataObject->salaryMax = $data['salary']['max'] ?? null;

        if (isset($data['addresses'])) {
            $locations = [];

            foreach ($data['addresses'] as $location) {
                if (isset($location['city'])) {
                    $locations[] = $location['city'];
                }
            }

            $jobDataObject->locations = $locations;
        }

        $jobDataObject->employmentType = $data['employment']['name'] ?? null;

        return $jobDataObject;
    }
}
