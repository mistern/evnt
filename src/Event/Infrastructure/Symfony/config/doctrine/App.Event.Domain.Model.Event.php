<?php

declare(strict_types=1);

use App\Event\Infrastructure\Doctrine\DBAL\Types\EventIdType;
use App\Event\Infrastructure\Doctrine\DBAL\Types\NameType;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\SlugType;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */
$builder = new ClassMetadataBuilder($metadata);

$builder->setTable('events');

$builder->createField('id', EventIdType::NAME)
    ->makePrimaryKey()
    ->generatedValue('NONE')
    ->build();

$builder->createField('name', NameType::NAME)->build();
$builder->createField('slug', SlugType::NAME)->unique()->build();
