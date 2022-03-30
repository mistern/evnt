<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model;

use Webmozart\Assert\Assert;

use function get_class;

/**
 * @psalm-immutable
 */
abstract class UuidEntityId
{
    private string $id;

    final private function __construct(string $id)
    {
        Assert::uuid($id);
        $this->id = $id;
    }

    /**
     * @psalm-pure
     */
    final public static function fromString(string $id): static
    {
        return new static($id);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    /**
     * @param static $otherId
     */
    public function equals(UuidEntityId $otherId): bool
    {
        return get_class($this) === get_class($otherId) && $this->id === $otherId->id;
    }
}
