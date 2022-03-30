<?php

declare(strict_types=1);

namespace App\Tests\Doubles\RamseyUuid;

use DateTimeInterface;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Validator\ValidatorInterface;

/**
 * @psalm-suppress all
 */
final class UuidFactoryDummy implements UuidFactoryInterface
{

    public function getValidator(): ValidatorInterface
    {
    }

    public function uuid1($node = null, ?int $clockSeq = null): UuidInterface
    {
    }

    public function uuid2(
        int $localDomain,
        ?IntegerObject $localIdentifier = null,
        ?Hexadecimal $node = null,
        ?int $clockSeq = null
    ): UuidInterface {
    }

    public function uuid3($ns, string $name): UuidInterface
    {
    }

    public function uuid4(): UuidInterface
    {
    }

    public function uuid5($ns, string $name): UuidInterface
    {
    }

    public function uuid6(?Hexadecimal $node = null, ?int $clockSeq = null): UuidInterface
    {
    }

    public function fromBytes(string $bytes): UuidInterface
    {
    }

    public function fromString(string $uuid): UuidInterface
    {
    }

    public function fromInteger(string $integer): UuidInterface
    {
    }

    public function fromDateTime(
        DateTimeInterface $dateTime,
        ?Hexadecimal $node = null,
        ?int $clockSeq = null
    ): UuidInterface {
    }
}
