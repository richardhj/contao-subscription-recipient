<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * General module fields
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_mailing_lists']    = array(
	'Selected/Selectable mailing lists',
	'Please choose the mailing lists, that will be subscribed or shown if the mailing lists input field is visible.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_recipient_fields'] = array(
	'Personal data',
	'Pleas choose additional personal data fields to ask for.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_form_target']      = array(
	'Formular target page (not confirmation page!)',
	'Please choose a page, the submitted form data will be posted (this page will set as &lt;form&gt; action!). Keep in mind that the module must be added on the target page!'
);


/**
 * Subscribe module fields
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_form_template']        = array(
	'Formular template',
	'Please choose the formular template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_confirmation_message'] = array(
	'Confirmation message',
	'Please choose the confirmation message boilerplate.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_confirmation_page']    = array(
	'Confirmation page',
	'Please choose the confirmation page.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_activation_page']    = array(
	'Activation page',
	'Please choose the activation page.'
);


/**
 * Unsubscribe module fields
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_show_mailing_lists'] = array(
	'Show mailing list selection',
	'Let the user choice which mailing lists should be unsubscribed.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_confirmation_message']    = array(
	'Confirmation message',
	'Please choose the confirmation message boilerplate.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_form_template']    = array(
	'Formular template',
	'Please choose the formular template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_confirmation_page']    = array(
	'Confirmation page',
	'Please choose the confirmation page.'
);


/**
 * Subscriptions module fields
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_form_template']        = array(
	'Formular template',
	'Please choose a custom formular template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_confirmation_message'] = array(
	'Confirmation page',
	'Please choose the confirmation page.'
);


$GLOBALS['TL_LANG']['tl_module']['avisota_categories']           = array(
	'Kategorien',
	'Wählen Sie die Kategorien aus, aus denen die Mailing angezeigt werden sollen.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_reader_template']      = array(
	'Leser-Template',
	'Wählen Sie hier das Template für den Mailing-Leser aus.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_list_template']        = array(
	'Listen-Template',
	'Wählen Sie hier das Template für die Mailing-Liste aus.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_view_page']            = array(
	'Ansichtsseite',
	'Wählen Sie hier eine Seite aus, auf der die Mailing angezeigt werden soll. Wird keine Seite ausgewählt, wird die in der Kategorie hinterlegte Seite zur Online-Ansicht verwendet.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe'] = array(
	'Form-Template',
	'Wählen Sie hier das Template für das Abmelden-Formular aus.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_legend'] = 'Abonnement';
$GLOBALS['TL_LANG']['tl_module']['avisota_mail_legend']         = 'Mail Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['avisota_notification_legend'] = 'Erinnerung';
$GLOBALS['TL_LANG']['tl_module']['avisota_cleanup_legend']      = 'Aufräumen';
$GLOBALS['TL_LANG']['tl_module']['avisota_reader_legend']       = 'Mailing-Leser';
$GLOBALS['TL_LANG']['tl_module']['avisota_list_legend']         = 'Mailing-Liste';
