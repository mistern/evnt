<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Application\Query\Pagination;
use App\Shared\Application\Query\PaginationFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;

final class HttpPaginationFactory implements PaginationFactory
{
    public function __construct(private int $maxPerPage, private RequestStack $requestStack)
    {
    }

    public function create(): Pagination
    {
        $request = $this->requestStack->getCurrentRequest();
        Assert::notNull($request);
        $currentPage = $request->query->getInt('page', 1);

        return new Pagination($currentPage, $this->maxPerPage);
    }
}
