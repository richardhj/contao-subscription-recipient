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
 * last-updated: 2014-03-25T14:15:13+01:00
 */

global $TL_LANG;

$FMD = array(
    'avisota_activation' => array(
        'Empfänger - Aktivierung',
    ),

    'avisota_subscribe' => array(
        'Empfänger - Bestellung',
    ),

    'avisota_subscription' => array(
        'Empfänger - Abonnement verwalten',
    ),

    'avisota_unsubscribe' => array(
        'Empfänger - Abmelden',
    ),
);

$TL_LANG['FMD'] = array_merge(
    $TL_LANG['FMD'],
    $FMD
);

$MOD = array(
    'avisota-subscription-recipient' => array(
        'Avisota - Bestellung für Empfänger',
        'Empfänger-Management und Abonnement für Avisota.',
    ),
);

$TL_LANG['MOD'] = array_merge(
    $TL_LANG['MOD'],
    $MOD
);
