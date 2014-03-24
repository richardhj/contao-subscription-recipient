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

use Avisota\Contao\Core\CoreEvents;
use Avisota\Contao\Message\Core\MessageEvents;
use Avisota\Contao\SubscriptionRecipient\RecipientDataContainerEvents;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory;

/**
 * Callbacks
 */
$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = array(
	'Avisota\Contao\Core\DataContainer\Module',
	'onload_callback'
);

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['metapalettes']['avisota_subscribe']    = array
(
	'title'                => array(
		'name',
		'headline',
		'type',
	),
	'avisota_subscription' => array(
		'avisota_mailing_lists',
		'avisota_recipient_fields',
		'avisota_subscribe_confirmation_message',
	),
	'template'             => array(
		'tableless',
		'avisota_subscribe_form_template',
		'avisota_form_target',
		'avisota_subscribe_confirmation_page',
	),
	'protected'            => array(
		':hide',
		'protected',
	),
	'expert'               => array(
		':hide',
		'guests',
		'cssID',
		'space',
	)
);
$GLOBALS['TL_DCA']['tl_module']['metapalettes']['avisota_unsubscribe']  = array
(
	'title'                => array(
		'name',
		'headline',
		'type'
	),
	'avisota_subscription' => array(
		'avisota_mailing_lists',
		'avisota_unsubscribe_confirmation_message',
	),
	'template'             => array(
		'tableless',
		'avisota_unsubscribe_form_template',
		'avisota_form_target',
		'avisota_unsubscribe_confirmation_page',
	),
	'protected'            => array(
		':hide',
		'protected',
	),
	'expert'               => array(
		':hide',
		'guests',
		'cssID',
		'space',
	)
);
$GLOBALS['TL_DCA']['tl_module']['metapalettes']['avisota_subscription'] = array
(
	'title'                => array(
		'name',
		'headline',
		'type'
	),
	'avisota_subscription' => array(
		'avisota_mailing_lists',
		'avisota_recipient_fields',
		'avisota_subscription_confirmation_message',
	),
	'template'             => array(
		'tableless',
		'avisota_subscription_form_template',
	),
	'protected'            => array(
		':hide',
		'protected'
	),
	'expert'               => array(
		':hide',
		'avisota_form_target',
		'guests',
		'cssID',
		'space'
	)
);

/**
 * General module fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_mailing_lists']    = array
(
	'exclude'          => true,
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['avisota_mailing_lists'],
	'inputType'        => 'checkbox',
	'options_callback' => CreateOptionsEventCallbackFactory::createCallback(CoreEvents::CREATE_MAILING_LIST_OPTIONS),
	'eval'             => array('multiple' => true)
);
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_recipient_fields'] = array
(
	'exclude'          => true,
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['avisota_recipient_fields'],
	'inputType'        => 'checkboxWizard',
	'options_callback' => CreateOptionsEventCallbackFactory::createCallback(
		RecipientDataContainerEvents::CREATE_IMPORTABLE_RECIPIENT_FIELD_OPTIONS
	),
	'eval'             => array('multiple' => true),
	'load_callback'    => array(
		array('Avisota\Contao\SubscriptionRecipient\DataContainer\Module', 'injectRequiredRecipientFields'),
	),
	'save_callback'    => array(
		array('Avisota\Contao\SubscriptionRecipient\DataContainer\Module', 'injectRequiredRecipientFields'),
	),
);
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_form_target']      = array
(
	'exclude'   => true,
	'label'     => &$GLOBALS['TL_LANG']['tl_module']['avisota_form_target'],
	'inputType' => 'pageTree',
	'eval'      => array('fieldType' => 'radio', 'tl_class' => 'clr')
);


/**
 * Subscribe module fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_subscribe_form_template']             = array
(
	'exclude'          => true,
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_form_template'],
	'inputType'        => 'select',
	'options_callback' => CreateOptionsEventCallbackFactory::createCallback(
		RecipientDataContainerEvents::CREATE_SUBSCRIBE_TEMPLATE_OPTIONS
	),
	'eval'             => array('tl_class' => 'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_subscribe_confirmation_message'] = array
(
	'exclude'          => true,
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_confirmation_message'],
	'inputType'        => 'select',
	'options_callback' => CreateOptionsEventCallbackFactory::createCallback(
		MessageEvents::CREATE_BOILERPLATE_MESSAGE_OPTIONS
	),
	'eval'             => array('tl_class' => 'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_subscribe_confirmation_page']    = array
(
	'exclude'   => true,
	'label'     => &$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_confirmation_page'],
	'inputType' => 'pageTree',
	'eval'      => array('fieldType' => 'radio')
);


/**
 * Unsubscribe module fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_unsubscribe_form_template']             = array
(
	'exclude'          => true,
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_form_template'],
	'inputType'        => 'select',
	'options_callback' => CreateOptionsEventCallbackFactory::createCallback(
		RecipientDataContainerEvents::CREATE_UNSUBSCRIBE_TEMPLATE_OPTIONS
	),
	'eval'             => array('tl_class' => 'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_unsubscribe_confirmation_message'] = array
(
	'exclude'          => true,
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_confirmation_message'],
	'inputType'        => 'select',
	'options_callback' => CreateOptionsEventCallbackFactory::createCallback(
		MessageEvents::CREATE_BOILERPLATE_MESSAGE_OPTIONS
	),
	'eval'             => array('tl_class' => 'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_unsubscribe_confirmation_page']    = array
(
	'exclude'   => true,
	'label'     => &$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_confirmation_page'],
	'inputType' => 'pageTree',
	'eval'      => array('fieldType' => 'radio')
);


/**
 * Subscriptions module fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_subscription_form_template']             = array
(
	'exclude'          => true,
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_form_template'],
	'inputType'        => 'select',
	'options_callback' => CreateOptionsEventCallbackFactory::createCallback(
		RecipientDataContainerEvents::CREATE_SUBSCRIPTION_TEMPLATE_OPTIONS
	),
	'eval'             => array('tl_class' => 'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_subscription_confirmation_message'] = array
(
	'exclude'          => true,
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_confirmation_message'],
	'inputType'        => 'select',
	'options_callback' => CreateOptionsEventCallbackFactory::createCallback(
		MessageEvents::CREATE_BOILERPLATE_MESSAGE_OPTIONS
	),
	'eval'             => array('tl_class' => 'w50')
);