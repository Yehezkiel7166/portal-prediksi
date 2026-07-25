<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Data\DomainHttpsProbeResult;
use Illuminate\Http\Client\Factory;
use Throwable;

class DomainHttpsProbe
{
    public function __construct(
        private readonly Factory $http,
    ) {}

    public function probe(string $host): DomainHttpsProbeResult
    {
        try {
            $response = $this->http
                ->connectTimeout(3)
                ->timeout(5)
                ->withOptions([
                    'allow_redirects' => false,
                    'verify' => true,
                ])
                ->withHeaders([
                    'User-Agent' => 'Portal-Prediksi-Domain-Health/1.0',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->head("https://{$host}/");

            return new DomainHttpsProbeResult(
                reachable: true,
                statusCode: $response->status(),
            );
        } catch (Throwable $exception) {
            return new DomainHttpsProbeResult(
                reachable: false,
                error: $exception->getMessage(),
            );
        }
    }
}
