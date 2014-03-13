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
 * Table orm_avisota_subscription
 * Entity Avisota\Contao:Subscription
 */
$GLOBALS['TL_DCA']['orm_avisota_subscription']['fields']['recipient'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['orm_avisota_subscription']['recipient'],
	'manyToOne' => array(
		'index'        => true,
		'targetEntity' => 'Avisota\Contao\Entity\Recipient',
		'cascade'      => array('persist', 'detach', 'merge', 'refresh'),
		'inversedBy'   => 'subscriptions',
		'joinColumns'  => array(
			array(
				'name'                 => 'recipient',
				'referencedColumnName' => 'id',
				'nullable'             => true,
			)
		),
	),
);
