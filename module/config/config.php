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


/**
 * Backend modules
 */
array_insert(
    $GLOBALS['BE_MOD']['avisota'],
    array_search('avisota_outbox', array_keys($GLOBALS['BE_MOD']['avisota'])),
    array(
        'avisota_recipients' => array(
            'tables'     => array(
                'orm_avisota_recipient',
                'mem_avisota_recipient_migrate',
                'orm_avisota_recipient_import',
                'mem_avisota_recipient_export',
                'orm_avisota_recipient_remove',
                'orm_avisota_recipient_notify',
            ),
            'icon'       => 'assets/avisota/subscription-recipient/images/recipients.png',
            'stylesheet' => 'assets/avisota/subscription-recipient/css/backend.css',
            'javascript' => 'assets/avisota/subscription-recipient/js/folding.js',
        )
    )
);


/**
 * Entities
 */
$GLOBALS['DOCTRINE_ENTITY_CLASS']['Avisota\Contao\Entity\Recipient'] =
    'Avisota\Contao\SubscriptionRecipient\Entity\AbstractRecipient';

$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient';


/**
 * Recipient sources
 */
$GLOBALS['AVISOTA_RECIPIENT_SOURCE']['recipients'] =
    'Avisota\Contao\SubscriptionRecipient\RecipientSource\RecipientsRecipientSourceFactory';

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['avisota']['avisota_subscribe']   = 'Avisota\Contao\SubscriptionRecipient\Module\Subscribe';
$GLOBALS['FE_MOD']['avisota']['avisota_activation']  = 'Avisota\Contao\SubscriptionRecipient\Module\Activation';
$GLOBALS['FE_MOD']['avisota']['avisota_unsubscribe'] = 'Avisota\Contao\SubscriptionRecipient\Module\Unsubscribe';
// $GLOBALS['FE_MOD']['avisota']['avisota_subscription'] = 'Avisota\Contao\SubscriptionRecipient\Module\Subscription';

/**
 * Events
 */
$GLOBALS['TL_EVENTS']['avisota/recipient.migrate-recipient'][] = array(
    'Avisota\Contao\SubscriptionRecipient\Recipient\Migrate',
    'collectPersonalsFromMembers'
);
$GLOBALS['TL_EVENTS']['avisota/subscription.collect-lists'][]  = array(
    'Avisota\Contao\SubscriptionRecipient\Recipient\Subscription',
    'collectSubscriptionLists'
);

/**
 * Event subscribers
 */
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\DataContainer\OptionsBuilder';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\Controller\RecipientController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = '\Avisota\Contao\SubscriptionRecipient\Controller\RescipiemtSporceController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\Controller\SubscriptionController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\Controller\DataContainerController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\Controller\ButtonController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\Controller\AutoCompleterController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\Controller\DoctrineManagerController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\Controller\MigrateRecipientsController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\Controller\RecipientTokenController';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\DataContainer\Recipient';
