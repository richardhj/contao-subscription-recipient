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
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Legend;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Palette;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Definition\PalettesDefinitionInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property\PropertyConditionChain;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property\PropertyConditionInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property\PropertyTrueCondition;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property\PropertyValueCondition;

/**
 * Table orm_avisota_recipient_source
 * Entity Avisota\Contao:RecipientSource
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['metapalettes']['recipients'] = array(
	'source'     => array('title', 'alias', 'type'),
	'recipients' => array('recipientsManageSubscriptionPage'),
	'details'    => array('recipientsDetails'),
	'filter'     => array(
		'filter',
		'filterByMailingLists',
		'recipientsUsePropertyFilter',
		function (
			$legendName,
			Legend $legend,
			Palette $palette,
			PalettesDefinitionInterface $palettesDefinition
		) {
			$filterByMailingListsProperty = $legend->getProperty('filterByMailingLists');
			$visibleCondition             = $filterByMailingListsProperty->getVisibleCondition();

			$typeCondition   = new PropertyValueCondition('type', 'recipients');
			$filterCondition = new PropertyTrueCondition('filter');

			/** @var PropertyConditionInterface|PropertyConditionChain $visibleCondition */
			if (
				!$visibleCondition ||
				!$visibleCondition instanceof PropertyConditionChain ||
				$visibleCondition->getConjunction() != PropertyConditionChain::OR_CONJUNCTION
			) {
				$visibleCondition = new PropertyConditionChain(
					$visibleCondition ? array($visibleCondition) : array(),
					PropertyConditionChain::OR_CONJUNCTION
				);
			}

			$visibleCondition->addCondition(
				new PropertyConditionChain(
					array($typeCondition, $filterCondition)
				)
			);

			$filterByMailingListsProperty->setVisibleCondition($visibleCondition);
		},
		function (
			$legendName,
			Legend $legend,
			Palette $palette,
			PalettesDefinitionInterface $palettesDefinition
		) {
			$recipientsUsePropertyFilterProperty = $legend->getProperty('recipientsUsePropertyFilter');
			$visibleCondition                    = $recipientsUsePropertyFilterProperty->getVisibleCondition();

			$typeCondition   = new PropertyValueCondition('type', 'recipients');
			$filterCondition = new PropertyTrueCondition('filter');

			/** @var PropertyConditionInterface|PropertyConditionChain $visibleCondition */
			if (
				!$visibleCondition ||
				!$visibleCondition instanceof PropertyConditionChain ||
				$visibleCondition->getConjunction() != PropertyConditionChain::AND_CONJUNCTION
			) {
				$visibleCondition = new PropertyConditionChain(
					$visibleCondition ? array($visibleCondition) : array()
				);
			}

			$visibleCondition->addCondition($typeCondition);
			$visibleCondition->addCondition($filterCondition);

			$recipientsUsePropertyFilterProperty->setVisibleCondition($visibleCondition);
		},
	),
	'expert'     => array('disable'),
);

$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['metasubpalettes']['recipientsUsePropertyFilter'] = array(
	'recipientsPropertyFilter',
);

$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['fields']['recipientsManageSubscriptionPage'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsManageSubscriptionPage'],
	'inputType' => 'pageTree',
	'eval'      => array(
		'mandatory' => true,
		'nullable'  => true,
	),
);

$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['fields']['recipientsDetails'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetails'],
	'default'   => 'recipients_details',
	'inputType' => 'select',
	'options'   => array('recipients_details', 'member_details', 'recipients_member_details'),
	'reference' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetailsTypes'],
	'eval'      => array(
		'mandatory' => true,
		'tl_class'  => 'w50',
	),
	'field'     => array(
		'length'   => '25',
		'options'  => array('fixed' => true),
		'nullable' => true,
	),
);

$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['fields']['recipientsUsePropertyFilter'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsUsePropertyFilter'],
	'inputType' => 'checkbox',
	'eval'      => array(
		'submitOnChange' => true,
	),
	'field'     => array(
		'nullable' => true,
	),
);

$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['fields']['recipientsPropertyFilter'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter'],
	'inputType' => 'multiColumnWizard',
	'eval'      => array(
		'mandatory'    => true,
		'columnFields' => array(
			'recipientsPropertyFilter_property'   => array(
				'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_property'],
				'inputType' => 'select',
				'reference' => &$GLOBALS['TL_LANG']['orm_avisota_recipient'],
				'eval'      => array(
					'style' => 'width:200px'
				),
			),
			'recipientsPropertyFilter_comparator' => array(
				'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparator'],
				'inputType' => 'select',
				'options'   => array('empty', 'not empty', 'eq', 'neq', 'gt', 'gte', 'lt', 'lte'),
				'reference' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators'],
				'eval'      => array(
					'style' => 'width:100px'
				),
			),
			'recipientsPropertyFilter_value'      => array(
				'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_value'],
				'inputType' => 'text',
				'eval'      => array(
					'style' => 'width:200px'
				),
			),
		),
	),
	'field'     => array(
		'type'     => 'json_array',
		'nullable' => true,
	),
);
