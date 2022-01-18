<?php

namespace Easy\Category\Contracts\Repository;

use Easy\Category\Models\Category as CatalogCategoryModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryInterface
{
    /**
     * Product table selectable fields
     */
    const SELECTABLE = [
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
     * getById
     *
     * @param int $id
     * @return CatalogCategoryModel
     */
    public function getById(int $id): CatalogCategoryModel;

    /**
     * @param string $orderColumn
     * @param string $direction
     * @param int $perPage
     * @param int $pageNumber
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getList(string $orderColumn = 'id', string $direction = 'DESC', int $perPage = 10,int $pageNumber = 1, array $filters = []) : LengthAwarePaginator;

    /**
     * @param string $orderColumn
     * @param string $direction
     * @param array $filters
     * @return Builder;
     */
    public function getListBuilder(string $orderColumn = 'id', string $direction = 'DESC', array $filters = []) : Builder;

    /**
     * store
     *
     * @param array $inputs
     * @return CatalogCategoryModel
     */
    public function create(array $inputs) : CatalogCategoryModel;

    /**
     * store
     *
     * @param array $inputs
     * @return bool
     */
    public function storeMultiples(array $inputs) : bool;

    /**
     * update
     *
     * @param array $inputs
     * @param int $id
     * @return CatalogCategoryModel
     */
    public function update(array $inputs, int $id) : CatalogCategoryModel;

    /**
     * delete
     *
     * @param array $ids
     * @return bool
     */
    public function delete(array $ids): bool;
}
