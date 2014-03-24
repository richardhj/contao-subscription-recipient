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
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['id']              = array(
	'ID'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['createdAt']       = array(
	'Created at'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['updatedAt']       = array(
	'Last modified at'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['email']           = array(
	'Email',
	'Please enter the email address.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['title']           = array(
	'Title',
	'Please enter the recipients title.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['forename']        = array(
	'Forename',
	'Please enter the recipients forename.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['surname']         = array(
	'Surname',
	'Please enter the recipients surname.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['gender']          = array(
	'Gender',
	'Please choose the recipients gender.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['mailingLists']          = array(
	'Mailing lists',
	''
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedById']       = array(
	'Added by user ID',
	'The ID of the user who added this recipient.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedByUsername'] = array(
	'Added by username',
	'The username of the user who added this recipient.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedByName']     = array(
	'Added by name',
	'The name of the user who added this recipient.'
);


// TODO remove
$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmed']          = array(
	'Confirmed',
	'This account has been confirmed.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['lists']              = array(
	'Mailing lists',
	'Please choose the subscribed mailing lists.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscriptionAction'] = array(
	'Activation',
	'Please choose the activation method for subscriptions on new mailing lists.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['recipient_legend']    = 'Recipient';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscription_legend'] = 'Subscription';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['personals_legend']    = 'Personals';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['added_at']          = 'added at %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['added_by']          = ', by <a href="%3$s">%1$s &lt;%2$s&gt;</a>';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['added_by_unlinked'] = ', by %1$s &lt;%2$s&gt;';

$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirm-subscription'] = 'Recipient <em>%1$s</em> subscription to <em>%2$s</em> was confirmed';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['remove-subscription']  = 'Recipient <em>%1$s</em> subscription to <em>%2$s</em> was removed';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscribe']            = 'Recipient <em>%1$s</em> subscribed to <em>%2$s</em>';

$GLOBALS['TL_LANG']['orm_avisota_recipient']['blacklisted'] = 'The recipient is blacklisted';

$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirm']                  = '%s neue Abonnenten wurden importiert.';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['invalid']                  = '%s ungültige Einträge wurden übersprungen.';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscribed']               = 'registriert am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['manually']                 = 'manuell hinzugefügt';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmManualActivation']  = 'Sind Sie sicher, dass Sie dieses Abonnement manuell aktivieren möchten?';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmationSent']         = 'Bestätigungsmail gesendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['reminderSent']             = 'Erinnerungsmail gesendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['remindersSent']            = '%d. Erinnerungsmail gesendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['sendConfirmation']         = 'Bestätigungsmail senden';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['activateSubscription']     = 'Abonnement direkt aktivieren';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['doNothink']                = 'Abonnement unbestätigt eintragen';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscription_global']      = 'Global subscription';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscription_mailingList'] = 'Mailing list: %s';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['new']                          = array(
	'New recipient',
	'Add a new recipient'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['show']                         = array(
	'Recipient details',
	'Show the details of recipient ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['delete']                       = array(
	'Delete recipient',
	'Delete recipient ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['edit']                         = array(
	'Edit recipient',
	'Edit recipient ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscribe_globally']           = 'Subscribe globally';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscribe']                    = 'Subscribe to this mailing list';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscribe_globally_confirmed'] = 'Subscribe and activate globally';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscribe_confirmed']          = 'Subscribe and activate to this mailing list';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirm_subscription']          = 'Confirm subscription';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['unsubscribe_globally']         = 'Unsubscribe globally';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['unsubscribe']                  = 'Unsubscribe from this mailing list';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['migrate']                      = array(
	'Migrate',
	'Migrate recipients from Contao newsletter system.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['import']                       = array(
	'CSV import',
	'Import recipients from a CSV file.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['export']                       = array(
	'CSV export',
	'Export recipients to a CSV file.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['remove']                       = array(
	'CSV delete',
	'Delete recipients from a CSV file.'
);
