<?php

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Models\Brand;

class BrandContext
{
    private ?Brand $brand = null;

    public function has(): bool
    {
        return $this->brand !== null;
    }

    public function get(): ?Brand
    {
        return $this->brand;
    }

    public function set(?Brand $brand): void
    {
        $this->brand = $brand;
    }

    public function clear(): void
    {
        $this->brand = null;
    }
}
