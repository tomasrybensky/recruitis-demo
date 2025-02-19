<?php

namespace App\Integrations\Recruitis;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ApplyForJobRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(public \App\Data\JobApplication $request)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/answers';
    }

    protected function defaultBody(): array
    {
        return [
            'job_id' => $this->request->jobId,
            'name' => $this->request->name,
            'email' => $this->request->email,
            'cover_letter' => $this->request->coverLetter,
        ];
    }
}