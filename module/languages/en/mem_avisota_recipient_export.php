<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['columns']    = array(
    'Columns assignment',
    'Please chose which fields should be exported.'
);
$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['delimiter']  = array(
    'Delimiter',
    'Please choose the CSV delimiter.'
);
$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['delimiters'] = array(
    'comma'     => 'Comma',
    'separator' => 'Separator',
    'space'     => 'Space',
    'tabulator' => 'Tabulator',
    'linebreak' => 'Line break',
);
$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['enclosure']  = array(
    'Enclosure',
    'Please choose the CSV enclosure.'
);
$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['enclosures'] = array(
    'double' => 'Double quote',
    'single' => 'Single quote',
);

/**
 * Legend
 */
$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['export_legend'] = 'Export settings';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['mem_avisota_recipient_export']['submit'] = 'Export';
