<?php

namespace Easy\Core\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Upload
{

    /**
     * @param $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return string
     */
    public function __invoke($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
//        $file = $args['file'];
        return (string) $context->request()->ip();
    }
}


