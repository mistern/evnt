<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure;

use App\Shared\Infrastructure\HttpPaginationFactory;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class HttpPaginationFactoryTest extends TestCase
{
    public function testItCreatesWithProvidedMaxPerPageValue(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request());
        $paginationFactory = new HttpPaginationFactory($maxPerPage = 99, $requestStack);

        $pagination = $paginationFactory->create();

        self::assertSame($maxPerPage, $pagination->maxPerPage);
    }

    public function testItCreatesWithCurrentPageValueFromRequest(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request(['page' => $currentPage = 99]));
        $paginationFactory = new HttpPaginationFactory(1, $requestStack);

        $pagination = $paginationFactory->create();

        self::assertSame($currentPage, $pagination->currentPage);
    }

    public function testItCreatesWithCurrentPageValueAsOneIfRequestQueryDoesNotContainPageParameter(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request());
        $paginationFactory = new HttpPaginationFactory(99, $requestStack);

        $pagination = $paginationFactory->create();

        self::assertSame(1, $pagination->currentPage);
    }

    public function testItFailsIfRequestStackDoesNotHaveCurrentRequest(): void
    {
        $requestStack = new RequestStack();
        $paginationFactory = new HttpPaginationFactory(1, $requestStack);

        $this->expectExceptionObject(new InvalidArgumentException('Expected a value other than null.'));

        $paginationFactory->create();
    }
}
