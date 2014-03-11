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


/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['avisota']['avisota_recipients'] = array(
	'tables'     => array(
		'orm_avisota_recipient',
		'orm_avisota_recipient_subscription',
		'mem_avisota_recipient_migrate',
		'orm_avisota_recipient_import',
		'orm_avisota_recipient_export',
		'orm_avisota_recipient_remove',
		'orm_avisota_recipient_notify',
	),
	'icon'       => 'system/modules/avisota/html/recipients.png',
	'stylesheet' => 'assets/avisota/core/css/stylesheet.css',
	'javascript' => 'assets/avisota/core/css/backend.js',
);


/**
 * Entities
 */
$GLOBALS['DOCTRINE_ENTITY_CLASS']['Avisota\Contao\Entity\Recipient'] = 'Avisota\Contao\Entity\AbstractRecipient';

$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_blacklist';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_subscription';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_subscription_log';


/**
 * Cron
 */
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronCleanupRecipientList');
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronNotifyRecipients');


/**
 * Recipient sources
 */
$GLOBALS['AVISOTA_RECIPIENT_SOURCE']['integrated']                 = 'Avisota\Contao\Core\RecipientSource\IntegratedRecipientsFactory';
$GLOBALS['AVISOTA_RECIPIENT_SOURCE']['integrated_by_mailing_list'] = 'Avisota\Contao\Core\RecipientSource\IntegratedRecipientsByMailingListFactory';

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['avisota']['avisota_subscribe']    = 'Avisota\Contao\SubscriptionRecipient\Module\Subscribe';
$GLOBALS['FE_MOD']['avisota']['avisota_unsubscribe']  = 'Avisota\Contao\SubscriptionRecipient\Module\Unsubscribe';
$GLOBALS['FE_MOD']['avisota']['avisota_subscription'] = 'Avisota\Contao\SubscriptionRecipient\Module\Subscription';

/**
 * Events
 */
$GLOBALS['TL_EVENTS']['avisota/recipient.migrate-recipient'][] = array(
	'Avisota\Contao\SubscriptionRecipient\Recipient\Migrate',
	'collectPersonalsFromMembers'
);
$GLOBALS['TL_EVENTS']['avisota/subscription.resolve-name'][]   = array(
	'Avisota\Contao\SubscriptionRecipient\Recipient\Subscription',
	'resolveSubscriptionName'
);
$GLOBALS['TL_EVENTS']['avisota/subscription.collect-lists'][]  = array(
	'Avisota\Contao\SubscriptionRecipient\Recipient\Subscription',
	'collectSubscriptionLists'
);

/**
 * Event subscribers
 */
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\SubscriptionRecipient\DataContainer\OptionsBuilder';
