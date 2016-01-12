<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2015 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\SubscriptionRecipient;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\SubscriptionRecipient\Event\RecipientAwareEvent;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class DoctrineBridgeSubscriber
 */
class DoctrineBridgeSubscriber implements EventSubscriber
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * DoctrineBridgeSubscriber constructor.
     *
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     *
     * @return $this
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist => 'postPersist',
            Events::postUpdate  => 'postUpdate',
            Events::postRemove  => 'postRemove',
        );
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $recipient = $eventArgs->getEntity();

        if ($recipient instanceof Recipient) {
            $event = new RecipientAwareEvent($recipient);
            $this->eventDispatcher->dispatch(RecipientEvents::CREATE_RECIPIENT, $event);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $recipient = $eventArgs->getEntity();

        if ($recipient instanceof Recipient) {
            $event = new RecipientAwareEvent($recipient);
            $this->eventDispatcher->dispatch(RecipientEvents::UPDATE_RECIPIENT, $event);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $recipient = $eventArgs->getEntity();

        if ($recipient instanceof Recipient) {
            $event = new RecipientAwareEvent($recipient);
            $this->eventDispatcher->dispatch(RecipientEvents::REMOVE_RECIPIENT, $event);
        }
    }
}
