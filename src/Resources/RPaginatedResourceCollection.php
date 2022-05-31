<?php

namespace SMSkin\LaravelSupport\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
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

    #[ArrayShape(['meta' => "\SMSkin\LaravelSupport\Resources\RMetaPagination", 'items' => "\Illuminate\Support\Collection"])]
    public function toArray($request): array
    {
        return [
            'meta' => $this->meta,
            'items' => $this->items,
        ];
    }
}
