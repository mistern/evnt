<?php

declare(strict_types=1);

namespace App\Tests\Doubles\RamseyUuid;

use DateTimeInterface;
use Exception;
use LogicException;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Validator\ValidatorInterface;

/**
 * @psalm-suppress all
 */
final class UuidFactoryStub implements UuidFactoryInterface
{
    public ValidatorInterface $validator;
    public UuidInterface|Exception $uuid1;
    public UuidInterface|Exception $uuid2;
    public UuidInterface|Exception $uuid3;
    public UuidInterface|Exception $uuid4;
    public UuidInterface|Exception $uuid5;
    public UuidInterface|Exception $uuid6;
    public UuidInterface|Exception $fromBytes;
    public UuidInterface|Exception $fromString;
    public UuidInterface|Exception $fromInteger;
    public UuidInterface|Exception $fromDateTime;

    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @throws Exception
     */
    public function uuid1($node = null, ?int $clockSeq = null): UuidInterface
    {
        if ($this->uuid1 instanceof UuidInterface) {
            return $this->uuid1;
        }
        if ($this->uuid1 instanceof Exception) {
            throw $this->uuid1;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function uuid2(
        int $localDomain,
        ?IntegerObject $localIdentifier = null,
        ?Hexadecimal $node = null,
        ?int $clockSeq = null
    ): UuidInterface {
        if ($this->uuid2 instanceof UuidInterface) {
            return $this->uuid2;
        }
        if ($this->uuid2 instanceof Exception) {
            throw $this->uuid2;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function uuid3($ns, string $name): UuidInterface
    {
        if ($this->uuid3 instanceof UuidInterface) {
            return $this->uuid3;
        }
        if ($this->uuid3 instanceof Exception) {
            throw $this->uuid3;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function uuid4(): UuidInterface
    {
        if ($this->uuid4 instanceof UuidInterface) {
            return $this->uuid4;
        }
        if ($this->uuid4 instanceof Exception) {
            throw $this->uuid4;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function uuid5($ns, string $name): UuidInterface
    {
        if ($this->uuid5 instanceof UuidInterface) {
            return $this->uuid5;
        }
        if ($this->uuid5 instanceof Exception) {
            throw $this->uuid5;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function uuid6(?Hexadecimal $node = null, ?int $clockSeq = null): UuidInterface
    {
        if ($this->uuid6 instanceof UuidInterface) {
            return $this->uuid6;
        }
        if ($this->uuid6 instanceof Exception) {
            throw $this->uuid6;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function fromBytes(string $bytes): UuidInterface
    {
        if ($this->fromBytes instanceof UuidInterface) {
            return $this->fromBytes;
        }
        if ($this->fromBytes instanceof Exception) {
            throw $this->fromBytes;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function fromString(string $uuid): UuidInterface
    {
        if ($this->fromString instanceof UuidInterface) {
            return $this->fromString;
        }
        if ($this->fromString instanceof Exception) {
            throw $this->fromString;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function fromInteger(string $integer): UuidInterface
    {
        if ($this->fromInteger instanceof UuidInterface) {
            return $this->fromInteger;
        }
        if ($this->fromInteger instanceof Exception) {
            throw $this->fromInteger;
        }

        throw new LogicException('Not implemented.');
    }

    /**
     * @throws Exception
     */
    public function fromDateTime(
        DateTimeInterface $dateTime,
        ?Hexadecimal $node = null,
        ?int $clockSeq = null
    ): UuidInterface {
        if ($this->fromDateTime instanceof UuidInterface) {
            return $this->fromDateTime;
        }
        if ($this->fromDateTime instanceof Exception) {
            throw $this->fromDateTime;
        }

        throw new LogicException('Not implemented.');
    }
}
