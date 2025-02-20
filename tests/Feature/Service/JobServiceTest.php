<?php

use App\Integrations\Recruitis\GetJobDetailRequest;
use App\Integrations\Recruitis\GetJobsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

uses(WebTestCase::class);

test('get jobs', function () {
    MockClient::global([
        GetJobsRequest::class => MockResponse::fixture('jobs'),
    ]);

    $client = static::createClient();
    $client->request('GET', '/api/jobs?page=1');
    $response = json_decode($client->getResponse()->getContent(), true);

    $jobExample = $response['payload'][0];

    // example of what could be tested
    expect(count($jobExample))->toBe(7)
        ->and($jobExample['title'])->toBe('Manažer segmentu trhu ve Fiber tribu KLON LHO 17 (m\z) test&test (m/z) test|@![]();test')
        ->and($jobExample['salaryMin'])->toBe(0)
        ->and($jobExample['salaryMax'])->toBe(35709);
});

test('job detail', function () {
    MockClient::global([
        GetJobDetailRequest::class => MockResponse::fixture('job'),
    ]);

    $client = static::createClient();
    $client->request('GET', '/api/jobs/431912');
    $job = json_decode($client->getResponse()->getContent(), true);

    // example of what could be tested
    expect(count($job))->toBe(7)
        ->and($job['title'])->toBe('Manažer segmentu trhu ve Fiber tribu KLON LHO 17 (m\z) test&test (m/z) test|@![]();test')
        ->and($job['salaryMin'])->toBe(0)
        ->and($job['salaryMax'])->toBe(35709);
});
