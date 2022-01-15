<?php

declare(strict_types=1);

namespace Easy\User\GraphQL\Mutations;

use Illuminate\Contracts\Auth\Authenticatable;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Easy\User\Services\Management\Authenticate;

/**
 * @Register
 */
class Register
{

    /**
     * @var Authenticate
     */
    private Authenticate $authenticate;

    /**
     * @param Authenticate $authenticate
     */
    public function __construct(Authenticate $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    /**
     * @param $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Authenticatable|null
     */
    public function __invoke($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?Authenticatable
    {
        $guard = config('sanctum.guard[0]', 'web');
        return $this->authenticate->registration($args, $guard);
    }
}
