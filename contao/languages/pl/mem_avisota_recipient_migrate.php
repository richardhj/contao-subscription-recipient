<?php

/**
 * Translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 *
 * @link    http://help.transifex.com/intro/translating.html
 * @link    https://www.transifex.com/projects/p/avisota-contao/language/pl/
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *
 * last-updated: 2014-03-11T15:25:41+01:00
 */

global $TL_LANG;

$memAvisotaRecipientMigrate = array(
    'migrate_legend'
    => 'Przenieś odbiorców',

    'migrated'
    => '%d odbiorców przeniesionych, %d pominiętych.',

    'channels' => array(
        'Kanały',
        'Proszę wybrać kanały i docelowe listy mailingowe.',
    ),

    'channels_channel' => array(
        'Kanał',
    ),

    'channels_mailingList' => array(
        'Lista mailingowa',
    ),

    'importFromMembers' => array(
        'Importuj dane osobiste z Użytkowników',
        'Importuj dane osobiste z użytkowników.',
    ),

    'overwrite' => array(
        'Nadpisz istniejących',
        'Nadpisz istniejących odbiorców.',
    ),
);

$TL_LANG['mem_avisota_recipient_migrate'] = array_merge(
    $TL_LANG['mem_avisota_recipient_migrate'] .
    $memAvisotaRecipientMigrate
);
