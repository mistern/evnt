<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;

final class PlatformStub extends AbstractPlatform
{
    public string $guidTypeDeclarationSQL = '';

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

    public function getGuidTypeDeclarationSQL(array $column): string
    {
        return $this->guidTypeDeclarationSQL;
    }

    protected function _getCommonIntegerTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    protected function initializeDoctrineTypeMappings(): void
    {
    }
}
