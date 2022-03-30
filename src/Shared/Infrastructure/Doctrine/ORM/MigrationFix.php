<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\ORM;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;

final class MigrationFix implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [ToolEvents::postGenerateSchema];
    }

    /**
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $eventArgs): void
    {
        $schema = $eventArgs->getSchema();
        if (!$schema->hasNamespace('public')) {
            $schema->createNamespace('public');
        }
    }
}
