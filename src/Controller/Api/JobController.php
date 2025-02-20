<?php

namespace App\Controller\Api;

use App\Data\JobApplication;
use App\Data\PaginationSetting;
use App\Services\JobService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class JobController extends AbstractController
{
    public function __construct(private JobService $jobService)
    {
    }

    #[Route('/jobs', name: 'api_jobs')]
    public function index(#[MapQueryParameter] int $page, ValidatorInterface $validator): Response
    {
        $paginationSetting = new PaginationSetting();
        $paginationSetting->page = $page;

        $errors = $validator->validate($paginationSetting);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $jobsResponse = $this->jobService->getJobs($paginationSetting);

        if ($jobsResponse) {
            return $this->json($jobsResponse);
        }

        return $this->json(['message' => 'Failed to get jobs'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/jobs/{id}', name: 'api_job')]
    public function show(int $id): Response
    {
        $job = $this->jobService->getJob($id);

        if ($job) {
            return $this->json($job);
        }

        return $this->json(['message' => 'Failed to get job'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/jobs/{id}/apply', name: 'api_job_apply', methods: ['POST'])]
    public function apply(#[MapRequestPayload] JobApplication $jobApplication): Response
    {
        if ($this->jobService->applyForJob($jobApplication)) {
            return $this->json(['message' => 'Applied for job']);
        }

        return $this->json(['message' => 'Failed to apply for job'], Response::HTTP_BAD_REQUEST);
    }
}
