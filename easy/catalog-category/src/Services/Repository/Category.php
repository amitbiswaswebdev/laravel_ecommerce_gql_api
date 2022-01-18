<?php

namespace Easy\Category\Services\Repository;

use Easy\Category\Contracts\Repository\CategoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Easy\Category\Models\Category as CatalogCategoryModel;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class Category implements CategoryInterface
{
    /**
     * CatalogCategory $catalogCategory
     */
    protected CatalogCategoryModel $catalogCategory;

    /**
     * construct
     *
     * @param CatalogCategoryModel $catalogCategory
     */
    public function __construct(CatalogCategoryModel $catalogCategory)
    {
        $this->catalogCategory = $catalogCategory;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): CatalogCategoryModel
    {
        return $this->catalogCategory::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(string $orderColumn = 'id', string $direction = 'DESC', int $perPage = 10, int $pageNumber = 1, array $filters = []): LengthAwarePaginator
    {
        return $this->getListBuilder($orderColumn, $direction, $filters)->paginate($perPage, self::SELECTABLE, 'page', $pageNumber);
    }

    /**
     * @inheritDoc
     */
    public function create(array $inputs): CatalogCategoryModel
    {
        return $this->catalogCategory::create($inputs);
    }

    /**
     * @inheritDoc
     */
    public function storeMultiples(array $inputs): bool
    {
        $this->productPriceModel::upsert($inputs, ['id']);
        return  true;
    }

    /**
     * @inheritDoc
     */
    public function update(array $inputs, int $id): CatalogCategoryModel
    {
        $this->catalogCategory::where('id', $id)->update($inputs);
        return $this->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(array $ids): bool
    {
        try {
            $this->catalogCategory::whereIn('id', $ids)->delete();
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function getListBuilder(string $orderColumn = 'id', string $direction = 'DESC', array $filters = []): Builder
    {
        return $this->catalogCategory::orderBy($orderColumn, $direction)->when($this->validFilters($filters), function ($query, $filters) {
            return $query->where($filters);
        });
    }

    /**
     * @param $filters
     * @return bool
     */
    protected function validFilters($filters): bool
    {
        if (sizeof($filters)){
            foreach ($filters as $filter) {
                if (!is_array($filter)) {
                    return false;
                } else if (sizeof($filter) == 3) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}
