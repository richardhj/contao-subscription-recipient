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

return array(
    'avisota/recipient.migrate-recipient' => array(
        array(Avisota\Contao\SubscriptionRecipient\Recipient\Migrate::class, 'collectPersonalsFromMembers')
    ),

    'avisota/subscription.collect-lists' => array(
        array(Avisota\Contao\SubscriptionRecipient\Recipient\Subscription::class, 'collectSubscriptionLists')
    ),
);
