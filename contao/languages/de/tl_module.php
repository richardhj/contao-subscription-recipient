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
 * last-updated: 2014-03-25T14:15:19+01:00
 */

global $TL_LANG;

$tlModule = array(
    'avisota_cleanup_legend'
    => 'Aufräumen',

    'avisota_list_legend'
    => 'Mailing-Liste',

    'avisota_mail_legend'
    => 'Mail Einstellungen',

    'avisota_notification_legend'
    => 'Erinnerung',

    'avisota_reader_legend'
    => 'Mailing-Leser',

    'avisota_subscription_legend'
    => 'Abonnement',

    'avisota_activation_confirmation_page' => array(
        'Bestätigungsseite',
    ),

    'avisota_activation_redirect_page' => array(
        'Weiterleitungsseite',
        'Bitte wählen Sie die Weiterleitungsseite aus. Dorthin wird ein Benutzer '
        . 'weitergeieitet wenn er die Seite ohne gültiges Token aufruft.',
    ),

    'avisota_categories' => array(
        'Kategorien',
        'Wählen Sie die Kategorien aus, aus denen die Mailing angezeigt werden sollen.',
    ),

    'avisota_form_target' => array(
        'Zielseite (NICHT die Bestätigungssette)',
    ),

    'avisota_list_template' => array(
        'Listen-Template',
        'Wählen Sie hier das Template für die Mailing-Liste aus.',
    ),

    'avisota_mailing_lists' => array(
        'Ausgewählte / auswählbare Mailinglisten',
    ),

    'avisota_reader_template' => array(
        'Leser-Template',
        'Wählen Sie hier das Template für den Mailing-Leser aus.',
    ),

    'avisota_recipient_fields' => array(
        'Persönliche Daten',
        'Bitte wählen Sie aus, welche Felder mit persönlichen Daten abgefragt werden sollen.',
    ),

    'avisota_subscribe_activation_page' => array(
        'Aktivierungsseite',
        'Bitte wählen Sie die Aktivierungsseite aus. Dorthin wird der AktIvierungslink leiten. Bitte beachten Sie, '
        . 'das Sie ein "Bestellungs-" oder ein "Aktivierungs"-Modul auf dieser Seite einbinden müssen.',
    ),

    'avisota_subscribe_confirmation_message' => array(
        'Bestätigungsnachricht',
        'Bitte wählen Sie eine Vorlage für die Bestätigungsnachricht aus.',
    ),

    'avisota_subscribe_form_template' => array(
        'Formular-Template',
        'Hier können Sie ein individuelles Formular-Template auswählen.',
    ),

    'avisota_subscription_confirmation_message' => array(
        'Bestätigungsseite',
        'Bitte wählen Sie die Bestätigungsseite aus.',
    ),
    'avisota_subscription_form_template'        => array(
        'Formular-Template',
        'Hier können Sie ein individuelles Formular-Template auswählen.',
    ),

    'avisota_template_unsubscribe' => array(
        'Form-Template',
        'Wählen Sie hier das Template für das Abmelden-Formular aus.',
    ),

    'avisota_unsubscribe_confirmation_message' => array(
        'Bestätigungsnachricht',
    ),

    'avisota_unsubscribe_confirmation_page' => array(
        'Bestätigungsseite',
        'Bitte wählen Sie die Seite aus, auf der das Abonnement bestätigt wird.',
    ),

    'avisota_unsubscribe_form_template' => array(
        'Formular-Template',
        'Hier können Sie ein individuelles Formular-Template auswählen.',
    ),
    'avisota_view_page'                 => array(
        'Ansichtsseite',
        'Wählen Sie hier eine Seite aus, auf der die Mailing angezeigt werden soll.'
        . ' Wird keine Seite ausgewählt, wird die in der Kategorie hinterlegte Seite zur Online-Ansicht verwendet.',
    ),
);

$TL_LANG['tl_module'] = array_merge(
    $TL_LANG['tl_module'],
    $tlModule
);
