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
 * last-updated: 2014-03-25T14:15:20+01:00
 */

global $TL_LANG;

$tlModule = array(
    'avisota_mail_legend'
    => 'Ustawienia e-mail',

    'avisota_notification_legend'
    => 'Pamięć',

    'avisota_cleanup_legend'
    => 'Czyszczenie',

    'avisota_subscription_legend'
    => 'Subskrypcja',

    'avisota_categories' => array(
        'Kategorie',
    ),

    'avisota_form_target' => array(
        'Strona docelowa formularza (nie strona potwierdzenia!)',
    ),

    'avisota_list_template' => array(
        'Szablon listy',
    ),

    'avisota_reader_template' => array(
        'Szablon listy',
    ),

    'avisota_recipient_fields' => array(
        'Dane osobiste',
        'Proszę wybrać dodatkowe dane osobiste, o które zostanie poproszony odbiorca.',
    ),

    'avisota_template_unsubscribe' => array(
        'Szablon formularza',
    ),
);

$TL_LANG['tl_module'] = array_merge(
    $TL_LANG['tl_module'],
    $tlModule
);
