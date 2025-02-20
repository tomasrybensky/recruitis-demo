<?php

namespace App\Services;

use App\Data\Job;
use App\Data\JobApplication;
use App\Data\PaginationSetting;
use App\Integrations\Recruitis\ApplyForJobRequest;
use App\Integrations\Recruitis\GetJobDetailRequest;
use App\Integrations\Recruitis\GetJobsRequest;
use App\Integrations\Recruitis\RecruitisConnector;
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

    public function getJobs(PaginationSetting $requestData): array
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
        $data['jobId'] = $data['job_id'];

        $jobDataObject = $this->serializer
            ->denormalize($data, Job::class, 'json');

        $jobDataObject->salaryMin = isset($data['salary']['is_min_visible']) ? $data['salary']['min'] ?? null : null;
        $jobDataObject->salaryMax = isset($data['salary']['is_max_visible']) ? $data['salary']['max'] ?? null : null;
        $jobDataObject->locations = array_filter(array_column($data['addresses'] ?? [], 'city'));
        $jobDataObject->employmentType = $data['employment']['name'] ?? null;

        return $jobDataObject;
    }
}
