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
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_avisota_settings']['metapalettes']['default']['notification'] = array('avisota_send_notification');
$GLOBALS['TL_DCA']['tl_avisota_settings']['metapalettes']['default']['cleanup']      = array('avisota_do_cleanup');

$GLOBALS['TL_DCA']['tl_avisota_settings']['metasubpalettes']['avisota_send_notification'] = array(
    'avisota_notification_time',
    'avisota_notification_count',
    'avisota_notification_mail'
);
$GLOBALS['TL_DCA']['tl_avisota_settings']['metasubpalettes']['avisota_do_cleanup']        = array(
    'avisota_cleanup_time'
);

$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_send_notification'] = array
(
    'exclude'   => true,
    'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_send_notification'],
    'inputType' => 'checkbox',
    'eval'      => array(
        'submitOnChange' => true,
        'tl_class'       => 'clr'
    )
);
$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_notification_time'] = array
(
    'exclude'   => true,
    'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_time'],
    'inputType' => 'text',
    'eval'      => array(
        'mandatory' => true,
        'rgxp'      => 'digit',
        'tl_class'  => 'w50'
    )
);
$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_notification_count'] = array
(
    'exclude'   => true,
    'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_count'],
    'inputType' => 'text',
    'eval'      => array(
        'mandatory' => true,
        'rgxp'      => 'digit',
        'tl_class'  => 'w50'
    )
);
$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_notification_mail'] = array
(
    'exclude'          => true,
    'label'            => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_mail'],
    'inputType'        => 'select',
    'options_callback' => CreateOptionsEventCallbackFactory::createCallback('avisota.create-boilerplate-message-options'),
    'eval'             => array(
        'mandatory'          => true,
        'includeBlankOption' => true,
        'tl_class'           => 'w50'
    )
);
$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_do_cleanup'] = array
(
    'exclude'   => true,
    'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_do_cleanup'],
    'inputType' => 'checkbox',
    'eval'      => array(
        'submitOnChange' => true,
        'tl_class'       => 'm12 w50 clr'
    )
);
$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_cleanup_time'] = array
(
    'exclude'   => true,
    'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_cleanup_time'],
    'default'   => 7,
    'inputType' => 'text',
    'eval'      => array(
        'mandatory' => true,
        'rgxp'      => 'digit',
        'tl_class'  => 'w50'
    )
);
