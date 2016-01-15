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
 * last-updated: 2014-04-07T04:01:35+02:00
 */

global $TL_LANG;

$tlAvisotaSettings = array(
    'subscription_recipient_legend'
    => 'Destinatari',

    'avisota_subscription_recipient_cleanup' => array(
        'Pulisci destinatari',
        'Quando un destinatario rimuove tutte le sue sottoscrizioni, rimuovilo.',
    ),
);

$TL_LANG['tl_avisota_settings'] = array_merge(
    $TL_LANG['tl_avisota_settings'],
    $tlAvisotaSettings
);
