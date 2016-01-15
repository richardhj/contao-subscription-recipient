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
 * last-updated: 2014-03-29T04:01:08+01:00
 */

global $TL_LANG;

$memAvisotaRecipientExport = array(
    'export_legend' => 'Configuraziun da l\'export',

    'submit' => 'Export',

    'columns' => array(
        'Attribur colonnas',
        'Tscherna ils champs che duain vegnir exportads',
    ),

    'delimiter' => array(
        'Limitader',
        'Tscherna il limitaderda CSV.',
    ),

    'delimiters' => array(
        'comma'     => 'Comma',
        'linebreak' => 'Nova lingia',
        'separator' => 'Seperatur',
        'space'     => 'Distanza',
        'tabulator' => 'Tabulatur',
    ),

    'enclosure' => array(
        'Brancar',
        'Tscherna il tip da brancar da CSV.',
    ),

    'enclosures' => array(
        'double' => 'Virgulettas dublas',
        'single' => 'Virgulettas simplas',
    ),
);

$TL_LANG['mem_avisota_recipient_export'] = array_merge(
    $TL_LANG['mem_avisota_recipient_export'],
    $memAvisotaRecipientExport
);
