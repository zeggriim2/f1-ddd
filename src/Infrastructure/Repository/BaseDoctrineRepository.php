<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Event\DomainEventDispatcherInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * DOCTRINE REPOSITORY : Implémentation avec base de données
 * - Gestion des événements automatique
 * - Transactions
 * - Requêtes optimisées
 */
abstract class BaseDoctrineRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        string $entityClass,
        protected DomainEventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($registry, $entityClass);
    }

    /**
     * ✅ GESTION AUTOMATIQUE DES ÉVÉNEMENTS
     */
    protected function saveWithEvents($entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        // Dispatcher les événements après la persistence
        if (method_exists($entity, 'getRecordedEvents')) {
            $this->eventDispatcher->dispatchAll($entity->getRecordedEvents());
            $entity->clearEvents();
        }
    }
}
