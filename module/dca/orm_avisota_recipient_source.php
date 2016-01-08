<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2015 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

use \ContaoCommunityAlliance\DcGeneral\DataDefinition;
use \ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property;

global $TL_DCA

/**
 * Table orm_avisota_recipient_source
 * Entity Avisota\Contao:RecipientSource
 */
$metaPalettes = array(
    'recipients' => array(
        'source'     => array('title', 'alias', 'type'),
        'recipients' => array('recipientsManageSubscriptionPage', 'recipientsUnsubscribePage'),
        'details'    => array('recipientsDetails'),
        'filter'     => array(
            'filter',
            'filterByMailingLists',
            'recipientsUsePropertyFilter',
            function (
                $legendName,
                DataDefinition\Palette\Legend $legend,
                DataDefinition\Palette\Palette $palette,
                DataDefinition\Definition\PalettesDefinitionInterface $palettesDefinition
            ) {
                $filterByMailingListsProperty = $legend->getProperty('filterByMailingLists');
                $visibleCondition             = $filterByMailingListsProperty->getVisibleCondition();

                $typeCondition   = new Property\PropertyValueCondition('type', 'recipients');
                $filterCondition = new Property\PropertyTrueCondition('filter');

                /** @var Property\PropertyConditionInterface|Property\PropertyConditionChain $visibleCondition */
                if (!$visibleCondition
                    || !$visibleCondition instanceof Property\PropertyConditionChain
                    || $visibleCondition->getConjunction() != Property\PropertyConditionChain::OR_CONJUNCTION
                ) {
                    $visibleCondition = new Property\PropertyConditionChain(
                        $visibleCondition ? array($visibleCondition) : array(),
                        Property\PropertyConditionChain::OR_CONJUNCTION
                    );
                }

                $visibleCondition->addCondition(
                    new Property\PropertyConditionChain(
                        array($typeCondition, $filterCondition)
                    )
                );

                $filterByMailingListsProperty->setVisibleCondition($visibleCondition);
            },
            function (
                $legendName,
                DataDefinition\Palette\Legend $legend,
                DataDefinition\Palette\Palette $palette,
                DataDefinition\Definition\PalettesDefinitionInterface $palettesDefinition
            ) {
                $recipientsUsePropertyFilterProperty = $legend->getProperty('recipientsUsePropertyFilter');
                $visibleCondition                    = $recipientsUsePropertyFilterProperty->getVisibleCondition();

                $typeCondition   = new Property\PropertyValueCondition('type', 'recipients');
                $filterCondition = new Property\PropertyTrueCondition('filter');

                /** @var Property\PropertyConditionInterface|Property\PropertyConditionChain $visibleCondition */
                if (!$visibleCondition
                    || !$visibleCondition instanceof Property\PropertyConditionChain
                    || $visibleCondition->getConjunction() != Property\PropertyConditionChain::AND_CONJUNCTION
                ) {
                    $visibleCondition = new Property\PropertyConditionChain(
                        $visibleCondition ? array($visibleCondition) : array()
                    );
                }

                $visibleCondition->addCondition($typeCondition);
                $visibleCondition->addCondition($filterCondition);

                $recipientsUsePropertyFilterProperty->setVisibleCondition($visibleCondition);
            },
        ),
        'expert'     => array('disable'),
    ),
);

$TL_DCA['orm_avisota_recipient_source']['metapalettes'] = array_merge(
    $TL_DCA['orm_avisota_recipient_source']['metapalettes'],
    $metaPalettes
);

$metaSubPalettes = array(
    'recipientsUsePropertyFilter' => array(
        'recipientsPropertyFilter',
    ),
);

$TL_DCA['orm_avisota_recipient_source']['metasubpalettes'] = array_merge(
    $TL_DCA['orm_avisota_recipient_source']['metasubpalettes'],
    $metaSubPalettes
);

$fields = array(
    'recipientsManageSubscriptionPage' => array(
        'label'     =>
            &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsManageSubscriptionPage'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'mandatory' => false,
            'nullable'  => true,
        ),
    ),

    'recipientsUnsubscribePage' => array(
        'label'     =>
            &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsUnsubscribePage'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'mandatory' => false,
            'nullable'  => true,
        ),
    ),

    'recipientsDetails' => array(
        'label'     =>
            &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetails'],
        'default'   => 'recipients_details',
        'inputType' => 'select',
        'options'   => array(
            'recipients_details',
            'member_details',
            'recipients_member_details',
        ),
        'reference' =>
            &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetailsTypes'],
        'eval'      => array(
            'mandatory' => true,
            'tl_class'  => 'w50',
        ),
        'field'     => array(
            'length'   => '25',
            'options'  => array(
                'fixed' => true,
            ),
            'nullable' => true,
        ),
    ),

    'recipientsUsePropertyFilter' => array(
        'label'     =>
            &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsUsePropertyFilter'],
        'inputType' => 'checkbox',
        'eval'      => array(
            'submitOnChange' => true,
        ),
        'field'     => array(
            'nullable' => true,
        ),
    ),

    'recipientsPropertyFilter' => array(
        'label'     =>
            &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter'],
        'inputType' => 'multiColumnWizard',
        'eval'      => array(
            'mandatory'    => true,
            'columnFields' => array(
                'recipientsPropertyFilter_property'   => array(
                    'label'     =>
                        &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_property'],
                    'inputType' => 'select',
                    'reference' =>
                        &$GLOBALS['TL_LANG']['orm_avisota_recipient'],
                    'eval'      => array(
                        'style' => 'width:200px',
                    ),
                ),
                'recipientsPropertyFilter_comparator' => array(
                    'label'     =>
                        &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparator'],
                    'inputType' => 'select',
                    'options'   => array(
                        'empty',
                        'not empty',
                        'eq',
                        'neq',
                        'gt',
                        'gte',
                        'lt',
                        'lte'
                    ),
                    'reference' =>
                        &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators'],
                    'eval'      => array(
                        'style' => 'width:100px',
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
    ),
);

$TL_DCA['orm_avisota_recipient_source']['fields'] = array_merge(
    $TL_DCA['orm_avisota_recipient_source']['fields'],
    $fields
);
