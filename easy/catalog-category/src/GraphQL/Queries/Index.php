<?php

declare(strict_types=1);

namespace Easy\Category\GraphQL\Queries;

use Easy\Category\Contracts\Management\CategoryInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * @Login
 */
class Index
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
     * @return Builder
     */
    public function __invoke($root, array $args): Builder
    {
        return $this->categoryInterface->getList(false, $args['filters'], $args['perPage'], $args['pageNumber']);
    }
}
