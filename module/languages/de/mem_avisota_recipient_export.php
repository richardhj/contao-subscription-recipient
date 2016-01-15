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
 * last-updated: 2014-03-29T04:01:08+01:00
 */

global $TL_LANG;

$memAvisotaRecipientExport = array(
    'export_legend'
    => 'Exporteinstellungen',

    'submit'
    => 'Export',

    'columns' => array(
        'Spaltenzuordnung',
    ),

    'delimiter' => array(
        'Begrenzer',
    ),

    'delimiters' => array(
        'comma'     => 'Komma',
        'linebreak' => 'Zeilenumbruch',
        'separator' => 'Trennelement',
        'space'     => 'Leerzeichen',
        'tabulator' => 'Tabulator',
    ),
);

$TL_LANG['mem_avisota_recipient_export'] = array_merge(
    $TL_LANG['mem_avisota_recipient_export'],
    $memAvisotaRecipientExport
);
