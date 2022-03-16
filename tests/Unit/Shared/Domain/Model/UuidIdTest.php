<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class UuidIdTest extends TestCase
{
    public function testItsValueIsAValidUuid(): void
    {
        $value = '746de104-2d4f-4577-8560-c358ec2ae8f0';
        $id = BasicUuidEntityId::fromString($value);

        self::assertSame($value, $id->toString(), 'Value of UUID ID should be a valid UUID.');
    }

    public function testItFailsWithNonUuidValue(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Value "a" is not a valid UUID.'));
        BasicUuidEntityId::fromString('a');
    }

    public function testItEqualsToOtherIdWithSameValue(): void
    {
        $id = BasicUuidEntityId::fromString('563f9db3-ac2c-4e39-b033-d21ad7031d65');
        $otherId = BasicUuidEntityId::fromString('563f9db3-ac2c-4e39-b033-d21ad7031d65');

        $isEqual = $id->equals($otherId);

        self::assertTrue($isEqual, 'UUID IDs with same values should be equal.');
    }

    public function testItDoesNotEqualToOtherIdWithDifferentValue(): void
    {
        $id = BasicUuidEntityId::fromString('0715112e-5073-4bef-9968-f5cef7ba757f');
        $otherId = BasicUuidEntityId::fromString('850dbbca-dab3-442b-8354-0ba3b6e390d9');

        $isEqual = $id->equals($otherId);

        self::assertFalse($isEqual, 'UUID IDs with different values should not be equal.');
    }
}
