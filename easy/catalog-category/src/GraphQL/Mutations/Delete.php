<?php

declare(strict_types=1);

namespace Easy\Category\GraphQL\Mutations;

use Easy\Category\Contracts\Management\CategoryInterface;
use Easy\Category\Models\Category as CatalogCategoryModel;

/**
 * @Login
 */
class Delete
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
     * @return bool
     */
    public function __invoke($root, array $args): bool
    {
        return $this->categoryInterface->delete($args['ids']);
    }
}
