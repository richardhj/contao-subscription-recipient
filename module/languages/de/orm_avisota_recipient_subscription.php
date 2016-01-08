<?php
/**
 * Translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 *
 * @link    http://help.transifex.com/intro/translating.html
 * @link    https://www.transifex.com/projects/p/avisota-contao/language/de/
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *
 * last-updated: 2014-03-25T14:15:16+01:00
 */

global $TL_LANG;

$ormAvisotaRecipientSubscription = array(
    'status_legend' => 'Status',

    'subscription_legend' => 'Abonnement',

    'subscription_list' => array(
        'mailing_list' => 'Mailinglisten',
    ),

    'confirmationSent' => array(
        'Bestätigung gesendet an',
    ),

    'confirmed' => array(
        'Bestätigt',
        'Dieses Abonnement wurde bestätigt.',
    ),

    'confirmedAt' => array(
        'Bestätigt am',
    ),

    'createdAt' => array(
        'Erstellt am',
    ),

    'delete' => array(
        'Abonnement löschen',
        'Abonnement ID %s löschen.',
    ),

    'edit' => array(
        'Abonnement bearbeiten',
        'Bearbeiten Sie das Abonnement ID %s.',
    ),

    'list' => array(
        'Abonnierte Liste',
    ),

    'new' => array(
        'Neues Abonnement',
    ),

    'recipient' => array(
        'Abonnent',
    ),

    'reminderCount' => array(
        'Erinnerung gesendet',
    ),

    'reminderSent' => array(
        'Bestätigung gesendet an',
    ),

    'token' => array(
        'Abonnements-Token',
    ),

    'updatedAt' => array(
        'Zuletzt bearbeitet am',
    ),
);

$TL_LANG['orm_avisota_recipient_subscription'] = array_merge(
    $TL_LANG['orm_avisota_recipient_subscription'],
    $ormAvisotaRecipientSubscription
);
