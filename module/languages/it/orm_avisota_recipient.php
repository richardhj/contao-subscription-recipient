<?php

/**
 * Translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 *
 * @link    http://help.transifex.com/intro/translating.html
 * @link    https://www.transifex.com/projects/p/avisota-contao/language/it/
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *
 * last-updated: 2014-04-07T04:01:30+02:00
 */

global $TL_LANG;

$ormAvisotaRecipient = array(
    'personals_legend'
    => 'Personali',

    'recipient_legend'
    => 'Destinatario',

    'subscription_legend'
    => 'Sottoscrizione',

    'confirmed' => array(
        'Confermato',
    ),

    'email' => array(
        'Email',
    ),

    'export' => array(
        'Esporta CSV',
    ),

    'import' => array(
        'Importa CSV',
    ),

    'remove' => array(
        'Elimina CSV',
    ),

    'subscriptionAction' => array(
        'Attivazione',
    ),

    'title' => array(
        'Titolo',
    ),
);

$TL_LANG['orm_avisota_recipient'] = array_merge(
    $TL_LANG['orm_avisota_recipient'],
    $ormAvisotaRecipient
);
