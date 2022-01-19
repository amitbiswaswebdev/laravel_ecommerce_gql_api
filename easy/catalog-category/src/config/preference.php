<?php
use Easy\Category\Contracts\Management\CategoryInterface;
use Easy\Category\Services\Management\Category;

return [
     'catalog-category-management' => [
         'source' => CategoryInterface::class,
         'destination' => Category::class,
         'remove' => false,
         'is_singleton' => false
     ]
];
