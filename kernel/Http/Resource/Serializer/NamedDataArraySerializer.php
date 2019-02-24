<?php declare(strict_types=1);

namespace Kernel\Http\Resource\Serializer;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class NamedDataArraySerializer
 * @package Kernel\Http\Resource\Serializer
 */
final class NamedDataArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data): array
    {
        return [$resourceKey ?? 'data' => $data];
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function item($resourceKey, array $data): array
    {
        return [$resourceKey ?? 'data' => $data];
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null(): array
    {
        return ['data' => []];
    }
}
