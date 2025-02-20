<?php

namespace App\Data;

class Job
{
    public int $jobId;
    public string $title;
    public string $description;
    public array $locations = [];
    public ?string $employmentType = null;
    public ?int $salaryMin = null;
    public ?int $salaryMax = null;
    public ?string $currency = null;
}
