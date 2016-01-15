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
 * last-updated: 2014-03-25T14:15:12+01:00
 */

global $TL_LANG;

$memAvisotaRecipientMigrate = array(
    'migrate_legend' => 'Empfänger übertragen',

    'migrated' => '%d Empfänger wurden übertragen, %d Empfänger wurden übersprungen.',

    'submit' => 'Migrieren',

    'channels' => array(
        'Kanäle',
        'Bitte wählen Sie die Kanäle und die Ziel-Mailinglisten.',
    ),

    'channels_channel' => array(
        'Kanal',
    ),

    'channels_mailingList' => array(
        'Mailingliste',
    ),

    'importFromMembers' => array(
        'Persönlichen Daten von Mitgliedern importieren',
        'Persönliche Daten von Mitgliedern importieren.',
    ),

    'overwrite' => array(
        'Bestehende überschreiben',
        'Die bereits vorhandenen Einträge werden überschrieben.',
    ),
);

$TL_LANG['mem_avisota_recipient_migrate'] = array_merge(
    $TL_LANG['mem_avisota_recipient_migrate'],
    $memAvisotaRecipientMigrate
);
