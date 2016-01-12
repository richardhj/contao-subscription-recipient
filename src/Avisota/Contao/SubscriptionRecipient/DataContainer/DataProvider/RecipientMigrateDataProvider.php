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

namespace Avisota\Contao\SubscriptionRecipient\DataContainer\DataProvider;


use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\Data\NoOpDataProvider;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class RecipientMigrateDataProvider
 *
 * @package Avisota\Contao\SubscriptionRecipient\DataContainer\DataProvider
 */
class RecipientMigrateDataProvider extends NoOpDataProvider
{
    /**
     * @param ModelInterface $objItem
     *
     * @return ModelInterface|void
     */
    public function save(ModelInterface $objItem)
    {
        global $container;

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $migrationSettings = $objItem->getPropertiesAsArray();

        $session = \Session::getInstance();

        do {
            $migrationId = substr(md5(mt_rand()), 0, 8);
        } while ($session->get('AVISOTA_MIGRATE_RECIPIENT_' . $migrationId));

        $session->set('AVISOTA_MIGRATE_RECIPIENT_' . $migrationId, $migrationSettings);

        $addToUrlEvent = new AddToUrlEvent('act=migrate&migration=' . rawurlencode($migrationId));
        $eventDispatcher->dispatch(ContaoEvents::BACKEND_ADD_TO_URL, $addToUrlEvent);

        $redirectEvent = new RedirectEvent($addToUrlEvent->getUrl());
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $redirectEvent);
    }
}
