<?php

namespace App\Data;

class Job
{
    public function __construct(
        public int $job_id,
        public string $title,
    )
    {}
}