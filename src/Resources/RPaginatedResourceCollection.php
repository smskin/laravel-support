<?php

namespace SMSkin\LaravelSupport\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema
 */
class RPaginatedResourceCollection extends ResourceCollection
{
    public Collection $items;

    /**
     * @Property
     */
    public RMetaPagination $meta;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->meta = new RMetaPagination($resource);
        $this->items = $this->collection;
    }

    public function toArray($request): array
    {
        return [
            'meta' => $this->meta,
            'items' => $this->items->map->toArray($request)->all()
        ];
    }
}
