<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\SubscriptionRecipient\Controller;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\SubscriptionNotificationCenterBridge\Event\BuildTokensFromRecipientEvent;
use Avisota\Contao\SubscriptionNotificationCenterBridge\SubscriptionNotificationCenterBridgeEvents;
use Contao\Doctrine\ORM\EntityAccessor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RecipientTokenController
 *
 * @package Avisota\Contao\SubscriptionRecipient\Controller
 */
class RecipientTokenController implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            SubscriptionNotificationCenterBridgeEvents::BUILD_TOKENS_FROM_RECIPIENT => array(
                array('buildRecipientTokens'),
            ),
        );
    }

    /**
     * @param BuildTokensFromRecipientEvent $event
     */
    public function buildRecipientTokens(BuildTokensFromRecipientEvent $event)
    {
        global $container;

        $recipient = $event->getRecipient();

        if (!$recipient instanceof Recipient) {
            return;
        }

        /** @var EntityAccessor $entityAccessor */
        $entityAccessor = $container['doctrine.orm.entityAccessor'];

        $tokens = $event->getTokens();

        $properties = $entityAccessor->getProperties($recipient);

        foreach ($properties as $key => $value) {
            if (!is_object($value)) {
                $tokens['recipient_' . $key] = $value;
            }
        }
    }
}
