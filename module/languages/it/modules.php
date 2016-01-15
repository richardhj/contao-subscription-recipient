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
 * last-updated: 2014-04-07T04:01:29+02:00
 */

global $TL_LANG;

$FMD = array(
    'avisota_activation' => array(
        'Destinatari - Attivazione',
    ),

    'avisota_subscribe' => array(
        'Destinatari - Sottoscrivi',
    ),

    'avisota_subscription' => array(
        'Destinatari - Gestisci sottoscrizione',
    ),

    'avisota_unsubscribe' => array(
        'Destinatari - Non sottoscritti',
    ),
);

$TL_LANG['FMD'] = array_merge(
    $TL_LANG['FMD'],
    $FMD
);

$MOD = array(
    'avisota-subscription-recipient' => array(
        'Avisota - Sottoscrizione per destinatari',
        'Gestione destinari e sottoscrizioni per Avisota.',
    ),
);

$TL_LANG['MOD'] = array_merge(
    $TL_LANG['MOD'],
    $MOD
);
