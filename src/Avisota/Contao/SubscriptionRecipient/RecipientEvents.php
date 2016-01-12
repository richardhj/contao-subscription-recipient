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
