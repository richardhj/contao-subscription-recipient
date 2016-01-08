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
