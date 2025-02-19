<?php

namespace App\Controller\Api;

use App\Data\JobApplication;
use App\Request\GetJobsRequest;
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
        $getJobsRequest = new GetJobsRequest();
        $getJobsRequest->page = $page;

        $errors = $validator->validate($getJobsRequest);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->jobService->getJobs($getJobsRequest);

        return $this->json($data);
    }

    #[Route('/jobs/{id}', name: 'api_job')]
    public function show(int $id): Response
    {
        $data = $this->jobService->getJob($id);

        return $this->json($data);
    }

    #[Route('/jobs/{id}/apply', name: 'api_job_apply', methods: ['POST'])]
    public function apply(#[MapRequestPayload] JobApplication $jobApplication): Response
    {
        $this->jobService->applyForJob($jobApplication);
        return $this->json(['message' => 'Applied for job']);
    }
}
