<?php

namespace App\Domains\Brand\Observers;

use App\Domains\Brand\Support\BrandContext;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class BrandOwnershipObserver
{
    public function __construct(
        private readonly BrandContext $brandContext,
    ) {}

    /**
     * Enforce Brand ownership before persistence.
     */
    public function creating(Model $model): void
    {
        if ($model->getAttribute('brand_id') !== null) {
            return;
        }

        $brand = $this->brandContext->get();

        if ($brand === null) {
            throw new LogicException(
                'Cannot create a brand-owned model without an active Brand context.',
            );
        }

        $model->setAttribute('brand_id', $brand->getKey());
    }
}
