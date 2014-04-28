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


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_avisota_settings']['metapalettes']['default']['subscription_recipient'] = array(
	'avisota_subscription_recipient_cleanup'
);

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_subscription_recipient_cleanup'] = array
(
	'exclude'   => true,
	'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_subscription_recipient_cleanup'],
	'inputType' => 'checkbox',
	'eval'      => array(
		'tl_class' => 'clr'
	)
);
