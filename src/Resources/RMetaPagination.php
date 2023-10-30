<?php

namespace SMSkin\LaravelSupport\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema
 */
class RMetaPagination extends JsonResource
{
    /**
     * @Property
     * @var int
     */
    public int $total;

    /**
     * @Property
     * @var int
     */
    public int $currentPage;

    /**
     * @Property
     * @var int
     */
    public int $lastPage;

    /**
     * @Property
     * @var int
     */
    public int $perPage;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->total = (int)$resource->total();
        $this->currentPage = (int)$resource->currentPage();
        $this->perPage = (int)$resource->perPage();
        $this->lastPage = (int)$resource->lastPage();
    }

    public function toArray($request): array
    {
        return [
            'total' => $this->total,
            'currentPage' => $this->currentPage,
            'lastPage' => $this->lastPage,
            'perPage' => $this->perPage,
        ];
    }
}
