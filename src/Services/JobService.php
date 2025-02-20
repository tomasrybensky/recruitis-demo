<?php

namespace App\Services;

use App\Data\Job;
use App\Data\JobApplication;
use App\Data\JobsResponse;
use App\Data\PaginationSetting;
use App\Integrations\Recruitis\ApplyForJobRequest;
use App\Integrations\Recruitis\GetJobDetailRequest;
use App\Integrations\Recruitis\GetJobsRequest;
use App\Integrations\Recruitis\RecruitisConnector;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class JobService
{
    public function __construct(
        private RecruitisConnector $connector,
        private ParameterBagInterface $parameterBag,
        private CacheInterface $cache,
    ) {
    }

    public function getJobs(PaginationSetting $requestData): ?JobsResponse
    {
        return $this->cache->get('jobs-'.$requestData->page, function (ItemInterface $item) use ($requestData) {
            $request = new GetJobsRequest($requestData->page);
            $response = $this->connector->send($request);

            if ($response->successful()) {
                $item->expiresAfter($this->parameterBag->get('cache_ttl'));

                return $response->dto();
            }

            return null;
        });
    }

    public function getJob(int $id): ?Job
    {
        return $this->cache->get('jobs-'.$id, function (ItemInterface $item) use ($id) {
            $request = new GetJobDetailRequest($id);
            $response = $this->connector->send($request);

            if ($response->successful()) {
                $item->expiresAfter($this->parameterBag->get('cache_ttl'));

                return $response->dto();
            }

            return null;
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

    public static function fillJobDataObject(array $data): Job
    {
        $job = new Job();
        $job->jobId = $data['job_id'];
        $job->title = $data['title'];
        $job->description = $data['description'];
        $job->salaryMin = isset($data['salary']['is_min_visible']) ? $data['salary']['min'] ?? null : null;
        $job->salaryMax = isset($data['salary']['is_max_visible']) ? $data['salary']['max'] ?? null : null;
        $job->locations = array_filter(array_column($data['addresses'] ?? [], 'city'));
        $job->employmentType = $data['employment']['name'] ?? null;
        $job->currency = $data['salary']['currency'] ?? null;

        return $job;
    }
}
