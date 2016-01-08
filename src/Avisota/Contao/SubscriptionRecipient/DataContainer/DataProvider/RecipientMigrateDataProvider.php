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

namespace Avisota\Contao\SubscriptionRecipient\DataContainer\DataProvider;

use Avisota\Contao\Subscription\SubscriptionManager;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent;
use Avisota\Contao\SubscriptionRecipient\RecipientEvents;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\Data\NoOpDataProvider;
use Doctrine\DBAL\Driver\PDOStatement;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RecipientMigrateDataProvider extends NoOpDataProvider
{
    /**
     * {@inheritdoc}
     */
    public function save(ModelInterface $objItem)
    {
        global $container;

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $migrationSettings = $objItem->getPropertiesAsArray();

        do {
            $migrationId = substr(md5(mt_rand()), 0, 8);
        } while (isset($_SESSION['AVISOTA_MIGRATE_RECIPIENT_' . $migrationId]));

        $_SESSION['AVISOTA_MIGRATE_RECIPIENT_' . $migrationId] = $migrationSettings;

        $addToUrlEvent = new AddToUrlEvent('act=migrate&migration=' . rawurlencode($migrationId));
        $eventDispatcher->dispatch(ContaoEvents::BACKEND_ADD_TO_URL, $addToUrlEvent);

        $redirectEvent = new RedirectEvent($addToUrlEvent->getUrl());
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $redirectEvent);
    }
}
