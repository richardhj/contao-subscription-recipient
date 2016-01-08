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
use Contao\Doctrine\ORM\EntityHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RecipientEvents
 */
class RecipientEvents
{
    /**
     * The CREATE_RECIPIENT event occurs when a recipient is created.
     *
     * This event allows you to manipulate the recipient, after it is added to the database. The event listener
     * method receives a Avisota\Contao\SubscriptionRecipient\Event\RecipientAwareEvent instance.
     *
     * @var string
     *
     * @api
     */
    const CREATE_RECIPIENT = 'avisota.subscription-recipient.create-recipient';

    /**
     * The UPDATE_RECIPIENT event occurs when a recipient is updated.
     *
     * This event allows you to manipulate the recipient, after it is updated in the database. The event listener
     * method receives a Avisota\Contao\SubscriptionRecipient\Event\RecipientAwareEvent instance.
     *
     * @var string
     *
     * @api
     */
    const UPDATE_RECIPIENT = 'avisota.subscription-recipient.update-recipient';

    /**
     * The REMOVE_RECIPIENT event occurs when a recipient is created.
     *
     * This event allows you to handle the recipient, after it has been removed from the database. The event
     * listener method receives a Avisota\Contao\SubscriptionRecipient\Event\RecipientAwareEvent instance.
     *
     * @var string
     *
     * @api
     */
    const REMOVE_RECIPIENT = 'avisota.subscription-recipient.remove-recipient';

    /**
     * The MIGRATE_RECIPIENT event occurs when a recipient gets imported.
     *
     * This event allows you to manipulate the recipient data on import. The event listener method receives
     * a Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent instance.
     *
     * @var string
     *
     * @api
     */
    const MIGRATE_RECIPIENT = 'avisota.subscription-recipient.migrate-recipient';

    /**
     * The EXPORT_RECIPIENT_PROPERTY event occurs when a recipient property must be converted for export.
     *
     * This event allows you to manipulate the recipient data on import. The event listener method receives
     * a Avisota\Contao\SubscriptionRecipient\Event\ExportRecipientPropertyEvent instance.
     *
     * @var string
     *
     * @api
     */
    const EXPORT_RECIPIENT_PROPERTY = 'avisota.subscription-recipient.export-recipient-property';
}
