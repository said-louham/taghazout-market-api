<?php

namespace App\Traits;

trait ResourceFilterable
{
    /**
     * Filter null inputs.
     */
    protected function fields($array): array
    {
        return collect($array)
            ->only(array_keys(array_merge($this->resource->getAttributes(), $this->resource->getRelations())))
            ->toArray();
    }
}
