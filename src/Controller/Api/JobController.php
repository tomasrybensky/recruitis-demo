<?php

namespace App\Controller\Api;

use App\Request\GetJobsRequest;
use App\Services\JobService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class JobController extends AbstractController
{
    #[Route('/jobs', name: 'api_jobs')]
    public function index(#[MapQueryParameter] int $page, JobService $jobService, ValidatorInterface $validator): Response
    {
        $getJobsRequest = new GetJobsRequest();
        $getJobsRequest->page = $page;

        $errors = $validator->validate($getJobsRequest);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $data = $jobService->getJobs($getJobsRequest);

        return $this->json(json_encode($data));
    }
}
