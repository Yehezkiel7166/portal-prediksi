<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Data\DomainVerificationCheck;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use Throwable;

class SeoDomainVerifier
{
    /**
     * @return list<DomainVerificationCheck>
     */
    public function verify(BrandDomain $domain): array
    {
        $host = strtolower(trim((string) $domain->host));

        if ($host === '') {
            return [
                $this->unknownCheck('canonical', 'Canonical'),
                $this->unknownCheck('robots_meta', 'Robots Meta'),
                $this->unknownCheck('robots_txt', 'Robots.txt'),
                $this->unknownCheck('sitemap', 'Sitemap'),
            ];
        }

        try {
            $response = Http::connectTimeout(5)
                ->timeout(10)
                ->withHeaders([
                    'User-Agent' => 'PortalPrediksi-DomainVerifier/1.0',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get("https://{$host}");

            if (! $response->successful() && ! $response->redirect()) {
                return [
                    $this->unknownCheck('canonical', 'Canonical'),
                    $this->unknownCheck('robots_meta', 'Robots Meta'),
                    $this->verifyRobotsTxt($host),
                    $this->verifySitemap($host),
                ];
            }

            $html = $response->body();

            return [
                $this->verifyCanonical($html, $host),
                $this->verifyRobotsMeta($html),
                $this->verifyRobotsTxt($host),
                $this->verifySitemap($host),
            ];
        } catch (Throwable $exception) {
            return [
                new DomainVerificationCheck(
                    key: 'seo_document',
                    label: 'SEO Document',
                    status: DomainVerificationStatus::Unknown,
                    message: 'SEO document could not be retrieved.',
                    weight: 1,
                    metadata: [
                        'error' => $exception->getMessage(),
                    ],
                ),
            ];
        }
    }

    private function verifyCanonical(
        string $html,
        string $host,
    ): DomainVerificationCheck {
        $document = $this->document($html);

        if (! $document instanceof DOMDocument) {
            return $this->unknownCheck('canonical', 'Canonical');
        }

        $links = $document->getElementsByTagName('link');

        foreach ($links as $link) {
            $relation = strtolower(trim($link->getAttribute('rel')));

            if ($relation !== 'canonical') {
                continue;
            }

            $canonical = trim($link->getAttribute('href'));

            if ($canonical === '') {
                break;
            }

            $canonicalHost = strtolower((string) parse_url($canonical, PHP_URL_HOST));

            if ($canonicalHost === $host) {
                return new DomainVerificationCheck(
                    key: 'canonical',
                    label: 'Canonical',
                    status: DomainVerificationStatus::Healthy,
                    message: 'Canonical URL points to the verified domain.',
                    weight: 2,
                    metadata: [
                        'url' => $canonical,
                    ],
                );
            }

            return new DomainVerificationCheck(
                key: 'canonical',
                label: 'Canonical',
                status: DomainVerificationStatus::Warning,
                message: 'Canonical URL points to another host.',
                weight: 2,
                metadata: [
                    'url' => $canonical,
                    'canonical_host' => $canonicalHost,
                ],
            );
        }

        return new DomainVerificationCheck(
            key: 'canonical',
            label: 'Canonical',
            status: DomainVerificationStatus::Warning,
            message: 'Canonical URL was not found.',
            weight: 2,
        );
    }

    private function verifyRobotsMeta(
        string $html,
    ): DomainVerificationCheck {
        $document = $this->document($html);

        if (! $document instanceof DOMDocument) {
            return $this->unknownCheck('robots_meta', 'Robots Meta');
        }

        $metaTags = $document->getElementsByTagName('meta');

        foreach ($metaTags as $meta) {
            $name = strtolower(trim($meta->getAttribute('name')));

            if ($name !== 'robots') {
                continue;
            }

            $content = strtolower(trim($meta->getAttribute('content')));

            if (str_contains($content, 'noindex')) {
                return new DomainVerificationCheck(
                    key: 'robots_meta',
                    label: 'Robots Meta',
                    status: DomainVerificationStatus::Warning,
                    message: 'The page contains a noindex directive.',
                    weight: 2,
                    metadata: [
                        'content' => $content,
                    ],
                );
            }

            return new DomainVerificationCheck(
                key: 'robots_meta',
                label: 'Robots Meta',
                status: DomainVerificationStatus::Healthy,
                message: 'Robots meta does not block indexing.',
                weight: 2,
                metadata: [
                    'content' => $content,
                ],
            );
        }

        return new DomainVerificationCheck(
            key: 'robots_meta',
            label: 'Robots Meta',
            status: DomainVerificationStatus::Healthy,
            message: 'No blocking robots meta directive was found.',
            weight: 2,
        );
    }

    private function verifyRobotsTxt(
        string $host,
    ): DomainVerificationCheck {
        try {
            $response = Http::connectTimeout(5)
                ->timeout(10)
                ->get("https://{$host}/robots.txt");

            if ($response->successful()) {
                return new DomainVerificationCheck(
                    key: 'robots_txt',
                    label: 'Robots.txt',
                    status: DomainVerificationStatus::Healthy,
                    message: 'robots.txt is accessible.',
                    weight: 1,
                    metadata: [
                        'status_code' => $response->status(),
                    ],
                );
            }

            return new DomainVerificationCheck(
                key: 'robots_txt',
                label: 'Robots.txt',
                status: DomainVerificationStatus::Warning,
                message: 'robots.txt is not accessible.',
                weight: 1,
                metadata: [
                    'status_code' => $response->status(),
                ],
            );
        } catch (Throwable $exception) {
            return new DomainVerificationCheck(
                key: 'robots_txt',
                label: 'Robots.txt',
                status: DomainVerificationStatus::Unknown,
                message: 'robots.txt could not be checked.',
                weight: 1,
                metadata: [
                    'error' => $exception->getMessage(),
                ],
            );
        }
    }

    private function verifySitemap(
        string $host,
    ): DomainVerificationCheck {
        try {
            $response = Http::connectTimeout(5)
                ->timeout(10)
                ->get("https://{$host}/sitemap.xml");

            if ($response->successful()) {
                return new DomainVerificationCheck(
                    key: 'sitemap',
                    label: 'Sitemap',
                    status: DomainVerificationStatus::Healthy,
                    message: 'sitemap.xml is accessible.',
                    weight: 1,
                    metadata: [
                        'status_code' => $response->status(),
                    ],
                );
            }

            return new DomainVerificationCheck(
                key: 'sitemap',
                label: 'Sitemap',
                status: DomainVerificationStatus::Warning,
                message: 'sitemap.xml is not accessible.',
                weight: 1,
                metadata: [
                    'status_code' => $response->status(),
                ],
            );
        } catch (Throwable $exception) {
            return new DomainVerificationCheck(
                key: 'sitemap',
                label: 'Sitemap',
                status: DomainVerificationStatus::Unknown,
                message: 'sitemap.xml could not be checked.',
                weight: 1,
                metadata: [
                    'error' => $exception->getMessage(),
                ],
            );
        }
    }

    private function document(string $html): ?DOMDocument
    {
        if (trim($html) === '') {
            return null;
        }

        $document = new DOMDocument;

        $previous = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML(
                $html,
                LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR,
            );
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        return $loaded ? $document : null;
    }

    private function unknownCheck(
        string $key,
        string $label,
    ): DomainVerificationCheck {
        return new DomainVerificationCheck(
            key: $key,
            label: $label,
            status: DomainVerificationStatus::Unknown,
            message: "{$label} could not be checked.",
            weight: 1,
        );
    }
}
