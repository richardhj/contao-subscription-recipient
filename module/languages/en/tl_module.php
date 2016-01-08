<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2015 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

global $TL_LANG;

$tlModule = array(
    'avisota_subscription_legend'
    => 'Abonnement',

    'avisota_mail_legend'
    => 'Mail Einstellungen',

    'avisota_notification_legend'
    => 'Erinnerung',

    'avisota_cleanup_legend'
    => 'Aufräumen',

    'avisota_reader_legend'
    => 'Mailing-Leser',

    'avisota_list_legend'
    => 'Mailing-Liste',

    'avisota_mailing_lists' => array(
        'Selected/Selectable mailing lists',
        'Please choose the mailing lists, that will be subscribed '
        . 'or shown if the mailing lists input field is visible.',
    ),

    'avisota_recipient_fields' => array(
        'Personal data',
        'Pleas choose additional personal data fields to ask for.',
    ),

    'avisota_form_target' => array(
        'Formular target page (not confirmation page!)',
        'Please choose a page, the submitted form data will be posted (this page will '
        . 'set as &lt;form&gt; action!). Keep in mind that the module must be added on the target page!',
    ),

    'avisota_subscribe_form_template' => array(
        'Formular template',
        'Please choose the formular template.',
    ),

    'avisota_subscribe_confirmation_message' => array(
        'Confirmation message',
        'Please choose the confirmation message boilerplate.',
    ),

    'avisota_subscribe_confirmation_page' => array(
        'Subscribe confirmation page',
        'Please choose the confirmation page. The user will be redirected '
        . 'to this page, after requesting a new subscription.',
    ),

    'avisota_subscribe_activation_page' => array(
        'Activation page',
        'Please choose the activation page. The activation link will be link to this page. '
        . 'Keep in mind that you need to add a "Subscription" or "Activation" module to this page.',
    ),

    'avisota_subscribe_activate_confirmation_page' => array(
        'Activation confirmation page',
        'Please choose the activated confirmation page. If you not use a separate activation page, '
        . 'the user will redirected to this page, after clicking on the activation link.',
    ),
    'avisota_activation_redirect_page'             => array(
        'Redirect page',
        'Please choose the redirect page. The user will be redirected to this page, '
        . 'if he call the page without a valid subscription token.',
    ),

    'avisota_activation_confirmation_page' => array(
        'Confirmation page',
        'Please choose the confirmation page. The user will redirected to this page, '
        . 'after clicking on the activation link.',
    ),

    'avisota_unsubscribe_show_mailing_lists' => array(
        'Show mailing list selection',
        'Let the user choice which mailing lists should be unsubscribed.',
    ),

    'avisota_unsubscribe_confirmation_message' => array(
        'Confirmation message',
        'Please choose the confirmation message boilerplate.',
    ),

    'avisota_unsubscribe_form_template' => array(
        'Formular template',
        'Please choose the formular template.',
    ),

    'avisota_unsubscribe_confirmation_page' => array(
        'Confirmation page',
        'Please choose the confirmation page.',
    ),

    'avisota_subscription_form_template' => array(
        'Formular template',
        'Please choose a custom formular template.',
    ),

    'avisota_subscription_confirmation_message' => array(
        'Confirmation page',
        'Please choose the confirmation page.',
    ),

    'avisota_categories' => array(
        'Kategorien',
        'Wählen Sie die Kategorien aus, aus denen die Mailing angezeigt werden sollen.',
    ),

    'avisota_reader_template' => array(
        'Leser-Template',
        'Wählen Sie hier das Template für den Mailing-Leser aus.',
    ),

    'avisota_list_template' => array(
        'Listen-Template',
        'Wählen Sie hier das Template für die Mailing-Liste aus.',
    ),

    'avisota_view_page' => array(
        'Ansichtsseite',
        'Wählen Sie hier eine Seite aus, auf der die Mailing angezeigt werden soll. '
        . 'Wird keine Seite ausgewählt, wird die in der Kategorie hinterlegte Seite zur Online-Ansicht verwendet.',
    ),

    'avisota_template_unsubscribe' => array(
        'Form-Template',
        'Wählen Sie hier das Template für das Abmelden-Formular aus.',
    ),
);

$TL_LANG['tl_module'] = array_merge(
    $TL_LANG['tl_module'],
    $tlModule
);
