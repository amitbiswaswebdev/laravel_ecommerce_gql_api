<?php

namespace Easy\Category\Contracts\Management;

use Easy\Category\Models\Category as CatalogCategoryModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryInterface
{

    /**
     * Product table selectable fields
     */
    const CATEGORY_SELECTABLE = [
        'id',
        'status',
        'title',
        'unique_name',
        'parent_id',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    /**
     * @param array $args
     * @return CatalogCategoryModel
     */
    public function create(array $args): CatalogCategoryModel;

    /**
     * @param array $args
     * @param int $id
     * @return CatalogCategoryModel
     */
    public function update(array $args, int $id): CatalogCategoryModel;

    /**
     * @param array $ids
     * @return bool
     */
    public function delete(array $ids): bool;

    /**
     * @param int $id
     * @return CatalogCategoryModel
     */
    public function get(int $id): CatalogCategoryModel;

    /**
     * @param bool $getPaginatedList
     * @param array $filters
     * @param int $perPage
     * @param int $pageNumber
     * @param string $orderColumn
     * @param string $direction
     * @return LengthAwarePaginator|Builder
     */
    public function getList(bool   $getPaginatedList = true,
                            array  $filters = [],
                            int    $perPage = 10,
                            int    $pageNumber = 1,
                            string $orderColumn = 'id',
                            string $direction = 'DESC'): LengthAwarePaginator|Builder;
}
