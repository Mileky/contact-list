<?php

/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace DD\ContactList\DoctrineEventSubscriber;

use DD\ContactList\Entity\Address;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Psr\Log\LoggerInterface;

/**
 * Подписчик на события связанные с сущностями
 */
class EntityEventSubscriber implements EventSubscriber
{
    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger - Логгер
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [Events::postLoad, Events::onFlush];
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $this->logger->debug('Event postLoad: ' . get_class($args->getEntity()));
    }


    private function dispatchInsertStatus($entityForInsert, UnitOfWork $uof): void
    {
        if ($entityForInsert instanceof Address\Status) {
            $uof->scheduleForDelete($entityForInsert);
        }
    }

    private function dispatchInsertTextDocument($entityForInsert, UnitOfWork $uof, EntityManagerInterface $em): void
    {
        if ($entityForInsert instanceof Address) {
            $oldStatus = $entityForInsert->getStatus();
            $entityStatus = $em->getRepository(Address\Status::class)
                ->findOneBy(['name' => $oldStatus->getName()]);
            $uof->propertyChanged($entityForInsert, 'status', $oldStatus, $entityStatus);
        }
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $uof = $args->getEntityManager()->getUnitOfWork();

        $entitiesForInsert = $uof->getScheduledEntityInsertions();
        $em = $args->getEntityManager();

        foreach ($entitiesForInsert as $entityForInsert) {
            $this->dispatchInsertStatus($entityForInsert, $uof);
            $this->dispatchInsertTextDocument($entityForInsert, $uof, $em);
        }
    }
}
