<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Data\DomainVerificationCheck;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class HttpDomainVerifier
{
    /**
     * @return list<DomainVerificationCheck>
     */
    public function verify(BrandDomain $domain): array
    {
        $host = strtolower(trim((string) $domain->host));

        if ($host === '') {
            return [
                new DomainVerificationCheck(
                    key: 'https_response',
                    label: 'HTTPS Response',
                    status: DomainVerificationStatus::Critical,
                    message: 'Domain host is empty.',
                    weight: 3,
                ),
            ];
        }

        $startedAt = microtime(true);

        try {
            $response = Http::connectTimeout(5)
                ->timeout(10)
                ->withHeaders([
                    'User-Agent' => 'PortalPrediksi-DomainVerifier/1.0',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get("https://{$host}");

            $responseTimeMs = (int) round(
                (microtime(true) - $startedAt) * 1000,
            );

            return [
                $this->responseCheck($response, $responseTimeMs),
                $this->hstsCheck($response),
            ];
        } catch (Throwable $exception) {
            return [
                new DomainVerificationCheck(
                    key: 'https_response',
                    label: 'HTTPS Response',
                    status: DomainVerificationStatus::Critical,
                    message: 'HTTPS request failed.',
                    weight: 3,
                    metadata: [
                        'error' => $exception->getMessage(),
                    ],
                ),
                new DomainVerificationCheck(
                    key: 'hsts',
                    label: 'HSTS',
                    status: DomainVerificationStatus::Unknown,
                    message: 'HSTS could not be checked.',
                    weight: 1,
                ),
            ];
        }
    }

    private function responseCheck(
        Response $response,
        int $responseTimeMs,
    ): DomainVerificationCheck {
        $statusCode = $response->status();

        $status = match (true) {
            $statusCode >= 200 && $statusCode < 400 => DomainVerificationStatus::Healthy,
            $statusCode >= 400 && $statusCode < 500 => DomainVerificationStatus::Warning,
            default => DomainVerificationStatus::Critical,
        };

        return new DomainVerificationCheck(
            key: 'https_response',
            label: 'HTTPS Response',
            status: $status,
            message: "HTTPS returned status {$statusCode}.",
            weight: 3,
            metadata: [
                'status_code' => $statusCode,
                'response_time_ms' => $responseTimeMs,
                'content_type' => $response->header('Content-Type'),
            ],
        );
    }

    private function hstsCheck(Response $response): DomainVerificationCheck
    {
        $header = $response->header('Strict-Transport-Security');

        if (! is_string($header) || trim($header) === '') {
            return new DomainVerificationCheck(
                key: 'hsts',
                label: 'HSTS',
                status: DomainVerificationStatus::Warning,
                message: 'Strict-Transport-Security header is missing.',
                weight: 1,
            );
        }

        return new DomainVerificationCheck(
            key: 'hsts',
            label: 'HSTS',
            status: DomainVerificationStatus::Healthy,
            message: 'Strict-Transport-Security header is active.',
            weight: 1,
            metadata: [
                'header' => $header,
            ],
        );
    }
}
