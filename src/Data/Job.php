<?php

namespace App\Data;

class Job
{
    public function __construct(
        public int $job_id,
        public string $title,
        public string $description,
        public array $locations = [],
        public ?string $employmentType = null,
        public ?int $salaryMin = null,
        public ?int $salaryMax = null,
    )
    {}
}