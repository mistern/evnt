<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Event\Domain\Model;

/**
 * @psalm-pure
 */
function anEventId(): EventIdBuilder
{
    return new EventIdBuilder();
}

/**
 * @psalm-pure
 */
function aName(): NameBuilder
{
    return new NameBuilder();
}

/**
 * @psalm-pure
 */
function aShortIntro(): ShortIntroBuilder
{
    return new ShortIntroBuilder();
}

/**
 * @psalm-pure
 */
function anEvent(): EventBuilder
{
    return new EventBuilder();
}

/**
 * @psalm-pure
 */
function anEventListOf(int $noItems): EventListBuilder
{
    return new EventListBuilder($noItems);
}
