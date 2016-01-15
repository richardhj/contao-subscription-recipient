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
 * last-updated: 2014-03-25T14:15:12+01:00
 */

global $TL_LANG;

$memAvisotaRecipientMigrate = array(
    'migrate_legend' => 'Migrar ils destinatur',

    'migrated' => '%d destinaturs èn vegnids migrads, %d surseglids.',

    'submit' => 'Migrar',

    'channels' => array(
        'Chanals',
        'Tscherna ils chanals e las glistas da mail da destinaziun.',
    ),

    'channels_channel' => array(
        'Chanal',
    ),

    'channels_mailingList' => array(
        'Glista da mail',
    ),

    'importFromMembers' => array(
        'Importar datas da commembers',
        'Impurtar las datas persunalas da commembers',
    ),

    'overwrite' => array(
        'Surscriver l\'existent',
        'Surscriver ils destinaturs existents.',
    ),
);

$TL_LANG['mem_avisota_recipient_migrate'] = array_merge(
    $TL_LANG['mem_avisota_recipient_migrate'],
    $memAvisotaRecipientMigrate
);
