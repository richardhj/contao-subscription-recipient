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

namespace Avisota\Contao\SubscriptionRecipient\Controller;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Subscription\Event\PrepareSubscriptionEvent;
use Avisota\Contao\Subscription\Event\ResolveRecipientEvent;
use Avisota\Contao\Subscription\Event\SubscriptionAwareEvent;
use Avisota\Contao\Subscription\SubscriptionEvents;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionController implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            SubscriptionEvents::UNSUBSCRIBE => array(
                array('cleanRecipient'),
            ),

            SubscriptionEvents::PREPARE_SUBSCRIPTION => array(
                array('prepareSubscription'),
            ),

            SubscriptionEvents::RESOLVE_RECIPIENT => array(
                array('resolveRecipient'),
            ),

            // todo if this event exist
            SubscriptionEvents::CREATE_RECIPIENT_PROPERTIES_OPTIONS => array(
                array('createRecipientPropertiesOptions'),
            ),
        );
    }

    /**
     * @param SubscriptionAwareEvent $event
     */
    public function cleanRecipient(SubscriptionAwareEvent $event)
    {
        if (!\Config::get('avisota_subscription_recipient_cleanup')) {
            return;
        }

        $subscription = $event->getSubscription();
        $recipient    = $subscription->getRecipient();

        if ($recipient) {
            $subscriptions = $recipient->getSubscriptions();

            if ($subscriptions->isEmpty()
                || $subscriptions->count() == 1
                   && $subscriptions->contains($subscription)
            ) {
                $entityManager = EntityHelper::getEntityManager();
                $entityManager->remove($recipient);
            }
        }
    }

    /**
     * @param PrepareSubscriptionEvent $event
     */
    public function prepareSubscription(PrepareSubscriptionEvent $event)
    {
        if ($event->getRecipient() instanceof Recipient) {
            $event->getSubscription()->setRecipient($event->getRecipient());
        }
    }

    /**
     * @param ResolveRecipientEvent $event
     */
    public function resolveRecipient(ResolveRecipientEvent $event)
    {
        // some other event listener has already resolved the recipient
        if ($event->getRecipient()) {
            return;
        }

        $subscription = $event->getSubscription();

        if ($subscription->getRecipientType() == 'Avisota\Contao\Entity\Recipient') {
            $entityManager = EntityHelper::getEntityManager();
            $repository    = $entityManager->getRepository('Avisota\Contao\Entity\Recipient');
            $recipient     = $repository->find($subscription->getRecipientId());
            $event->setRecipient($recipient);
        }
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createRecipientPropertiesOptions(CreateOptionsEvent $event)
    {
        $options = $event->getOptions();

        #$baseSubscriber = new \Avisota\Contao\SubscriptionRecipient\EventsSubscriber();
        $baseSubscriber = new EventsSubscriber();
        $options = $baseSubscriber->getRecipientPropertiesOptions($event->getDataContainer()->getEnvironment(), $options);

        $event->setOptions($options);
    }
}
