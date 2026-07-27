<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

final readonly class HttpsPolicyData
{
    public function __construct(
        public bool $forceHttps,
        public bool $sendHsts,
        public int $hstsMaxAge,
        public bool $includeSubDomains,
        public bool $preload,
        public int $redirectStatus,
    ) {}

    /**
     * @return array{
     *     force_https: bool,
     *     send_hsts: bool,
     *     hsts_max_age: int,
     *     include_sub_domains: bool,
     *     preload: bool,
     *     redirect_status: int
     * }
     */
    public function toArray(): array
    {
        return [
            'force_https' => $this->forceHttps,
            'send_hsts' => $this->sendHsts,
            'hsts_max_age' => $this->hstsMaxAge,
            'include_sub_domains' => $this->includeSubDomains,
            'preload' => $this->preload,
            'redirect_status' => $this->redirectStatus,
        ];
    }
}
