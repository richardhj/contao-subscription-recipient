<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory;

/**
 * Table mem_avisota_recipient_migrate
 */
$GLOBALS['TL_DCA']['mem_avisota_recipient_migrate'] = array
(
	// Config
	'config'       => array
	(
		'dataContainer' => 'General',
		'forceEdit'     => true,
	),
	// DataContainer
	'dca_config'   => array
	(
		'data_provider' => array
		(
			'default' => array
			(
				'class' => 'Avisota\Contao\Core\DataContainer\DataProvider\RecipientMigrateDataProvider',
			),
		),
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'migrate' => array('channels', 'overwrite', 'importFromMembers'),
		)
	),
	// Fields
	'fields'       => array
	(
		'channels'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels'],
			'inputType' => 'multiColumnWizard',
			'eval'      => array(
				'columnFields' => array
				(
					'channel'     => array
					(
						'label'      => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels_channel'],
						'inputType'  => 'select',
						'foreignKey' => 'tl_newsletter_channel.title',
						'eval'       => array(
							'style'              => 'width:250px',
							'mandatory'          => true,
							'includeBlankOption' => true,
							'chosen'             => true,
						)
					),
					'mailingList' => array
					(
						'label'            => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels_mailingList'],
						'inputType'        => 'select',
						'options_callback' => CreateOptionsEventCallbackFactory::createCallback('avisota.create-mailing-list-options'),
						'eval'             => array(
							'style'              => 'width:250px',
							'mandatory'          => true,
							'includeBlankOption' => true,
							'chosen'             => true,
						)
					),
				),
				'mandatory'    => true,
				'multiple'     => true
			),
		),
		'overwrite'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['overwrite'],
			'inputType' => 'checkbox',
		),
		'importFromMembers' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['importFromMembers'],
			'inputType' => 'checkbox',
		),
	)
);
