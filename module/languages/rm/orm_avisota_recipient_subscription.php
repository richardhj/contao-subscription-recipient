<?php
/**
 * Translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 *
 * @link    http://help.transifex.com/intro/translating.html
 * @link    https://www.transifex.com/projects/p/avisota-contao/language/rm/
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *
 * last-updated: 2014-03-11T15:25:44+01:00
 */

global $TL_LANG;

$ormAvisotaRecipientSubscription = array(
    'status_legend' => 'Status',

    'subscription_legend' => 'Abunament',

    'subscription_list' => array(
        'global'       => 'abunament global',
        'mailing_list' => 'Glistas da mail',
    ),

    'confirmationSent' => array(
        'Confermaziun tramess ils',
    ),

    'confirmed' => array(
        'Confermà',
        'Quest abunament è vegnì confermà.',
    ),

    'confirmedAt' => array(
        'Confermà las',
    ),

    'createdAt' => array(
        'Creà las',
    ),

    'delete' => array(
        'Stizzar l\'abunament',
        'Stizzar l\'abunament cun l\'ID %s',
    ),

    'edit' => array(
        'Modifitgar l\'abunament',
        'Modifitgar l\'abunament cun l\'ID %s',
    ),

    'list' => array(
        'Glista abunada',
        'Tscherna la glista abunada.',
    ),

    'new' => array(
        'Nov abunament',
        'Agiuntar in nov abunament',
    ),

    'recipient' => array(
        'Destinatur',
    ),

    'reminderCount' => array(
        'Tramess regurdanzas',
    ),

    'reminderSent' => array(
        'Regurdanza tramess ils',
    ),

    'show' => array(
        'Detagls da l\'abunament',
        'Mussar ils detagls da l\'abunament cun l\'ID %s',
    ),

    'token' => array(
        'Token d\'abunar',
    ),

    'updatedAt' => array(
        'Ultima modificaziun las',
    ),
);

$TL_LANG['orm_avisota_recipient_subscription'] = array_merge(
    $TL_LANG['orm_avisota_recipient_subscription'],
    $ormAvisotaRecipientSubscription
);
