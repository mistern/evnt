<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;

final class PlatformDummy extends AbstractPlatform
{
    public function getBooleanTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    public function getIntegerTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    public function getBigIntTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    public function getSmallIntTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    public function getClobTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    public function getBlobTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    public function getName(): string
    {
        return '';
    }

    public function getCurrentDatabaseExpression(): string
    {
        return '';
    }

    protected function _getCommonIntegerTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    protected function initializeDoctrineTypeMappings(): void
    {
    }
}
