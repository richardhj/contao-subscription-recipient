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

use Avisota\Contao\Entity\Subscription;
use Avisota\Contao\SubscriptionRecipient\Event\ExportRecipientPropertyEvent;
use Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent;
use Avisota\Contao\SubscriptionRecipient\RecipientEvents;
use Contao\Doctrine\ORM\EntityAccessor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RecipientController
 *
 * @package Avisota\Contao\SubscriptionRecipient\Controller
 */
class RecipientController implements EventSubscriberInterface
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
            RecipientEvents::MIGRATE_RECIPIENT => array(
                array('collectMemberPersonals'),
            ),

            RecipientEvents::EXPORT_RECIPIENT_PROPERTY => array(
                array('exportRecipientProperties'),
            ),
        );
    }

    /**
     * While migrate Contao newsletter recipients, fill with member details.
     *
     * @param MigrateRecipientEvent $event
     */
    public function collectMemberPersonals(MigrateRecipientEvent $event)
    {
        global $container;

        $migrationSettings = $event->getMigrationSettings();

        if ($migrationSettings['importFromMembers']) {
            $recipient = $event->getRecipient();

            /** @var \Doctrine\DBAL\Connection $connection */
            $connection = $container['doctrine.connection.default'];

            $queryBuilder = $connection->createQueryBuilder();

            /** @var \PDOStatement $stmt */
            $stmt   = $queryBuilder
                ->select('*')
                ->from('tl_member', 'm')
                ->where(
                    $queryBuilder
                        ->expr()
                        ->eq('m.email', ':email')
                )
                ->setParameter('email', $recipient->getEmail())
                ->execute();
            $member = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($member) {
                /** @var EntityAccessor $entityAccessor */
                $entityAccessor = $container['doctrine.orm.entityAccessor'];

                foreach ($member as $key => $value) {
                    // graceful conversions
                    switch ($key) {
                        case 'firstname':
                            $key = 'forename';
                            break;
                        case 'lastname':
                            $key = 'surname';
                            break;
                    }

                    if ($entityAccessor->hasProperty($recipient, $key)) {
                        $entityAccessor->setProperty($recipient, $key, $value);
                    }
                }
            }
        }
    }

    /**
     * @param ExportRecipientPropertyEvent $event
     */
    public function exportRecipientProperties(ExportRecipientPropertyEvent $event)
    {
        switch ($event->getPropertyName()) {
            case 'mailingListIds':
            case 'mailingListNames':
                if ($event->getString() !== null) {
                    return;
                }

                $subscriptions = $event->getRecipient()->getSubscriptions();
                $values        = array();

                /** @var Subscription $subscription */
                foreach ($subscriptions as $subscription) {
                    if (!$subscription->getActive()) {
                        continue;
                    }

                    $mailingList = $subscription->getMailingList();

                    if ($mailingList) {
                        switch ($event->getPropertyName()) {
                            case 'mailingListIds':
                                $values[] = $mailingList->getId();
                                break;
                            case 'mailingListNames':
                                $values[] = $mailingList->getTitle();
                                break;
                        }
                    } else {
                        $values[] = 'global';
                    }
                }

                $event->setString(implode("\n", $values));
                break;
        }
    }
}
