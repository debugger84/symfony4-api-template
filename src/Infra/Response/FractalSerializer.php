<?php


namespace App\Infra\Response;


use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\DataArraySerializer;

class FractalSerializer extends DataArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return [$resourceKey => $data];
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return [$resourceKey => $data];
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null()
    {
        return [];
    }

    /**
     * Serialize the meta.
     *
     * @param array $meta
     *
     * @return array
     */
    public function meta(array $meta)
    {
        if (empty($meta)) {
            return [];
        }

        return $meta;
    }

    /**
     * Serialize the paginator.
     *
     * @param PaginatorInterface $paginator
     *
     * @return array
     */
    public function paginator(PaginatorInterface $paginator)
    {
        $currentPage = (int) $paginator->getCurrentPage();

        $pagination = [
            'total' => (int) $paginator->getLastPage(),
            'perPage' => (int) $paginator->getPerPage(),
            'page' => $currentPage,
        ];

        return ['pagination' => $pagination];
    }
}
