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
                'class'  =>
                    'Avisota\Contao\SubscriptionRecipient\DataContainer\DataProvider\RecipientMigrateDataProvider',
                'source' => 'mem_avisota_recipient_migrate',
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
                        'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels_mailingList'],
                        'inputType' => 'select',
                        'eval'      => array(
                            'style'              => 'width:250px',
                            'mandatory'          => true,
                            'includeBlankOption' => true,
                            'chosen'             => true,
                        ),
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
        'ignoreBlacklist'   => array
        (
            'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['ignoreBlacklist'],
            'inputType' => 'checkbox',
        ),
        'importFromMembers' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['importFromMembers'],
            'inputType' => 'checkbox',
        ),
    )
);
