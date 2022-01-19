<?php

declare(strict_types=1);

namespace Easy\Category\GraphQL\Queries;

use Easy\Category\Contracts\Management\CategoryInterface;
use Easy\Category\Models\Category as CatalogCategoryModel;

/**
 * @Login
 */
class Show
{

    /**
     * @var CategoryInterface
     */
    private CategoryInterface $categoryInterface;

    /**
     * @param CategoryInterface $categoryInterface
     */
    public function __construct(CategoryInterface $categoryInterface)
    {
        $this->categoryInterface = $categoryInterface;
    }

    /**
     * @param $root
     * @param array $args
     * @return CatalogCategoryModel
     */
    public function __invoke($root, array $args): CatalogCategoryModel
    {
        return $this->categoryInterface->get($args['id']);
    }
}
