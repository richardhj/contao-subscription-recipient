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
