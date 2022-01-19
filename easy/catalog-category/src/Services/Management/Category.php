<?php

namespace Easy\Category\Services\Management;

use Easy\Category\Contracts\Management\CategoryInterface;
use Easy\Category\Models\Category as CatalogCategoryModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function create(array $args): CatalogCategoryModel
    {
        return $this->catalogCategory::create($args);
    }

    /**
     * @inheritDoc
     */
    public function update(array $args, int $id): CatalogCategoryModel
    {
        $this->catalogCategory::where('id', $id)->update($args);
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
    public function get(int $id): CatalogCategoryModel
    {
        return $this->catalogCategory::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(
        bool   $getPaginatedList = true,
        array  $filters = [],
        int    $perPage = 10,
        int    $pageNumber = 1,
        string $orderColumn = 'id',
        string $direction = 'DESC'
    ): LengthAwarePaginator|Builder
    {
        $builder = $this->catalogCategory::orderBy($orderColumn, $direction)->when($this->validFilters($filters), function ($query, $filters) {
            return $query->where($filters);
        });
        return ($getPaginatedList) ? $builder->paginate($perPage, self::CATEGORY_SELECTABLE, 'page', $pageNumber) : $builder;
    }

    /**
     * @param $filters
     * @return bool
     */
    protected function validFilters($filters): bool
    {
        if (sizeof($filters)) {
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
