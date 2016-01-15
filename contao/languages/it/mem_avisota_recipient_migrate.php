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
 * last-updated: 2014-04-07T04:01:28+02:00
 */

global $TL_LANG;

$memAvisotaRecipientMigrate = array(
    'channels' => array(
        'Canali',
    ),

    'channels_channel' => array(
        'Canale',
    ),

    'channels_mailingList' => array(
        'Mailing list',
    ),

    'overwrite' => array(
        'Sovrascrivi esistente',
        'Sovrascrivi destinatari esistenti.',
    ),
);

$TL_LANG['mem_avisota_recipient_migrate'] = array_merge(
    $TL_LANG['mem_avisota_recipient_migrate'],
    $memAvisotaRecipientMigrate
);
