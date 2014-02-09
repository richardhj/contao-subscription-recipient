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
	'stylesheet' => 'assets/avisota-core/css/stylesheet.css',
	'javascript' => 'assets/avisota-core/css/backend.js',
);


/**
 * Entities
 */
$GLOBALS['DOCTRINE_ENTITY_CLASS']['Avisota\Contao\Entity\Recipient'] = 'Avisota\Contao\Entity\AbstractRecipient';

$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_blacklist';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_source';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_subscription';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_subscription_log';


/**
 * Cron
 */
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronCleanupRecipientList');
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronNotifyRecipients');
