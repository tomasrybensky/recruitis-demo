<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class GetJobsRequest
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $page;
}