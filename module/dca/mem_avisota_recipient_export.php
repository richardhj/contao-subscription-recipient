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

use \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory;
use \Avisota\Contao\SubscriptionRecipient\RecipientDataContainerEvents;

/**
 * Table mem_avisota_recipient_export
 */
$GLOBALS['TL_DCA']['mem_avisota_recipient_export'] = array
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
                'class'  => 'Avisota\Contao\SubscriptionRecipient'
                            . '\DataContainer\DataProvider\RecipientExportDataProvider',
                'source' => 'mem_avisota_recipient_export',
            ),
        ),
    ),
    // Palettes
    'metapalettes' => array
    (
        'default' => array
        (
            'export' => array('columns', 'delimiter', 'enclosure'),
        )
    ),
    // Fields
    'fields'       => array
    (
        'columns'   => array
        (
            'label'            => &$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['columns'],
            'inputType'        => 'checkboxWizard',
            'options_callback' => CreateOptionsEventCallbackFactory::createCallback(
                RecipientDataContainerEvents::CREATE_IMPORTABLE_RECIPIENT_FIELD_OPTIONS,
                'Avisota\Contao\Core\Event\CreateOptionsEvent'
            ),
            'eval'             => array(
                'mandatory' => true,
                'multiple'  => true,
            ),
        ),
        'delimiter' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['delimiter'],
            'inputType' => 'select',
            'options'   => array('comma', 'semicolon', 'space', 'tabulator', 'linebreak'),
            'reference' => &$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['delimiters'],
            'eval'      => array(
                'mandatory' => true,
                'tl_class'  => 'clr w50',
            ),
        ),
        'enclosure' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['enclosure'],
            'inputType' => 'select',
            'options'   => array('double', 'single'),
            'reference' => &$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['enclosures'],
            'eval'      => array(
                'mandatory' => true,
                'tl_class'  => 'w50',
            ),
        ),
    )
);
