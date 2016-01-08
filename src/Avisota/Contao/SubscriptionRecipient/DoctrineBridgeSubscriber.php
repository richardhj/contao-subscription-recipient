<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\SubscriptionRecipient;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Subscription\Event\PrepareSubscriptionEvent;
use Avisota\Contao\Subscription\Event\ResolveRecipientEvent;
use Avisota\Contao\Subscription\SubscriptionEvents;
use Avisota\Contao\SubscriptionRecipient\Event\CreateRecipientEvent;
use Avisota\Contao\SubscriptionRecipient\Event\RecipientAwareEvent;
use Contao\Doctrine\DBAL\DoctrineDbalEvents;
use Contao\Doctrine\DBAL\Event\InitializeEventManager;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DoctrineBridgeSubscriber
 */
class DoctrineBridgeSubscriber implements EventSubscriber
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param EventDispatcher $eventDispatcher
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
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist => 'postPersist',
            Events::postUpdate  => 'postUpdate',
            Events::postRemove  => 'postRemove',
        );
    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $recipient = $eventArgs->getEntity();

        if ($recipient instanceof Recipient) {
            $event = new RecipientAwareEvent($recipient);
            $this->eventDispatcher->dispatch(RecipientEvents::CREATE_RECIPIENT, $event);
        }
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $recipient = $eventArgs->getEntity();

        if ($recipient instanceof Recipient) {
            $event = new RecipientAwareEvent($recipient);
            $this->eventDispatcher->dispatch(RecipientEvents::UPDATE_RECIPIENT, $event);
        }
    }

    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $recipient = $eventArgs->getEntity();

        if ($recipient instanceof Recipient) {
            $event = new RecipientAwareEvent($recipient);
            $this->eventDispatcher->dispatch(RecipientEvents::REMOVE_RECIPIENT, $event);
        }
    }
}
