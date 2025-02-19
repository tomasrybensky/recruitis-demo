<?php

namespace App\Data;

use Symfony\Component\Validator\Constraints as Assert;

class JobApplication
{
    #[Assert\NotBlank]
    public int $jobId;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(max: 4000)]
    public string $coverLetter;

}