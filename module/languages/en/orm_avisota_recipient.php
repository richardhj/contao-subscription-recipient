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

global $TL_LANG;

$ormAvisotaRecipient = array(
    'recipient_legend' =>
        'Recipient',

    'subscription_legend' =>
        'Subscription',

    'personals_legend' =>
        'Personals',

    'added_at' =>
        'added at %s',

    'added_by' =>
        ', by <a href="%3$s">%1$s &lt;%2$s&gt;</a>',

    'added_by_unlinked' =>
        ', by %1$s &lt;%2$s&gt;',

    'confirm-subscription' =>
        'Recipient <em>%1$s</em> subscription to <em>%2$s</em> was confirmed',

    'remove-subscription' =>
        'Recipient <em>%1$s</em> subscription to <em>%2$s</em> was removed',

    'subscribe' =>
        'Subscribe to this mailing list',

    'blacklisted' =>
        'The recipient is blacklisted',

    'confirm' =>
        '%s neue Abonnenten wurden importiert.',

    'invalid' =>
        '%s ungültige Einträge wurden übersprungen.',

    'subscribed' =>
        'registriert am %s',

    'manually' =>
        'manuell hinzugefügt',

    'confirmManualActivation' =>
        'Sind Sie sicher, dass Sie dieses Abonnement manuell aktivieren möchten?',

    'confirmationSent' =>
        'Bestätigungsmail gesendet am %s',

    'reminderSent' =>
        'Erinnerungsmail gesendet am %s',

    'remindersSent' =>
        '%d. Erinnerungsmail gesendet am %s',

    'sendConfirmation' =>
        'Bestätigungsmail senden',

    'activateSubscription' =>
        'Abonnement direkt aktivieren',

    'doNothink' =>
        'Abonnement unbestätigt eintragen',

    'subscription_global' =>
        'Global subscription',

    'subscription_mailingList' =>
        'Mailing list: %s',

    'subscribe_globally' =>
        'Subscribe globally',

    'subscribe_globally_confirmed' =>
        'Subscribe and activate globally',

    'subscribe_confirmed' =>
        'Subscribe and activate to this mailing list',

    'confirm_subscription' =>
        'Confirm subscription',

    'unsubscribe_globally' =>
        'Unsubscribe globally',

    'unsubscribe' =>
        'Unsubscribe from this mailing list',

    'id' => array(
        'ID',
    ),

    'createdAt' => array(
        'Created at',
    ),

    'updatedAt' => array(
        'Last modified at',
    ),

    'email' => array(
        'Email',
        'Please enter the email address.',
    ),

    'title' => array(
        'Title',
        'Please enter the recipients title.',
    ),

    'forename' => array(
        'Forename',
        'Please enter the recipients forename.',
    ),

    'surname' => array(
        'Surname',
        'Please enter the recipients surname.',
    ),

    'gender' => array(
        'Gender',
        'Please choose the recipients gender.',
    ),

    'company' => array(
        'Company',
        'Here you can enter a company name.',
    ),

    'street' => array(
        'Street',
        'Please enter the street name and number.',
    ),

    'postal' => array(
        'Postal code',
        'Please enter the postal code.',
    ),

    'city' => array(
        'City',
        'Plase enter the name of the city.',
    ),

    'state' => array(
        'State',
        'Plase enter the name of the state.',
    ),

    'country' => array(
        'Country',
        'Please select the country.',
    ),

    'mailingLists' => array(
        'Mailing lists',
    ),

    'mailingListIds' => array(
        'Mailing list IDs',
    ),

    'mailingListNames' => array(
        'Mailing list names',
    ),

    'addedById' => array(
        'Added by user ID',
        'The ID of the user who added this recipient.',
    ),

    'addedByUsername' => array(
        'Added by username',
        'The username of the user who added this recipient.',
    ),

    'addedByName' => array(
        'Added by name',
        'The name of the user who added this recipient.',
    ),

    'confirmed' => array(
        'Confirmed',
        'This account has been confirmed.',
    ),

    'lists' => array(
        'Mailing lists',
        'Please choose the subscribed mailing lists.',
    ),

    'subscriptionAction' => array(
        'Activation',
        'Please choose the activation method for subscriptions on new mailing lists.',
    ),

    'new' => array(
        'New recipient',
        'Add a new recipient',
    ),

    'show' => array(
        'Recipient details',
        'Show the details of recipient ID %s',
    ),

    'delete' => array(
        'Delete recipient',
        'Delete recipient ID %s',
    ),

    'edit' => array(
        'Edit recipient',
        'Edit recipient ID %s',
    ),

    'migrate' => array(
        'Migrate',
        'Migrate recipients from Contao newsletter system.',
    ),

    'import' => array(
        'CSV import',
        'Import recipients from a CSV file.',
    ),

    'export' => array(
        'CSV export',
        'Export recipients to a CSV file.',
    ),

    'remove' => array(
        'CSV delete',
        'Delete recipients from a CSV file.',
    ),
);

$TL_LANG['orm_avisota_recipient'] = array_merge(
    $TL_LANG['orm_avisota_recipient'],
    $ormAvisotaRecipient
);
