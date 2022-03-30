<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Doctrine\DBAL\Types;

use App\Shared\Infrastructure\Doctrine\DBAL\Types\UuidEntityIdType;
use App\Tests\Unit\Shared\Domain\Model\BasicUuidEntityId;

final class BasicUuidEntityIdType extends UuidEntityIdType
{
    public const NAME = '__basic_uuid_entity_id';

    protected static function getClassName(): string
    {
        return BasicUuidEntityId::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
