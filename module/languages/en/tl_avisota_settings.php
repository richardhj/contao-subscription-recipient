<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Fields
 */
// notification
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_send_notification']                = array(
	'Send notification',
	'Send a notification, if the subscription is not confirmed after certain days.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_time']                = array(
	'Days until notification is send',
	'Please enter the number of days before the notification should be send.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_count']               = array(
	'Notification count',
	'Please enter the number of notification to send. The time between notifications will be increase by 50%.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_mail']  = array(
	'Notification mail',
	'Please chose the notification mail boilerplate.'
);

// cleanup
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_do_cleanup']   = array(
	'Delete unconfirmed subscribers',
	'Delete subscribers that not confirm their subscription after certain days.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_cleanup_time'] = array(
	'Days until deletion',
	'Please enter the number of days before unconfirmed subscribers will be deleted.'
);

/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_avisota_settings']['notification_legend']            = 'Notification';
$GLOBALS['TL_LANG']['tl_avisota_settings']['cleanup_legend']                 = 'Cleanup';
