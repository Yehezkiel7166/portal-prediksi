<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

final readonly class CanonicalUrlData
{
    public function __construct(
        public string $url,
        public string $scheme,
        public string $host,
        public string $path,
        public bool $usesPrimaryDomain,
        public bool $indexable,
        public string $robots,
    ) {}

    /**
     * @return array{
     *     url: string,
     *     scheme: string,
     *     host: string,
     *     path: string,
     *     uses_primary_domain: bool,
     *     indexable: bool,
     *     robots: string
     * }
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'scheme' => $this->scheme,
            'host' => $this->host,
            'path' => $this->path,
            'uses_primary_domain' => $this->usesPrimaryDomain,
            'indexable' => $this->indexable,
            'robots' => $this->robots,
        ];
    }
}
