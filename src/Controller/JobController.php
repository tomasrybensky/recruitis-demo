<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JobController extends AbstractController
{
    #[Route('/', name: 'jobs')]
    public function index(): Response
    {
        return $this->render('job/index.html.twig');
    }

    #[Route('/jobs/{id}', name: 'job')]
    public function show(int $id): Response
    {
        return $this->render('job/show.html.twig', ['id' => $id]);
    }
}
