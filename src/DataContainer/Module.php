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

namespace Avisota\Contao\SubscriptionRecipient\DataContainer;

use Bit3\Contao\MetaPalettes\MetaPalettes;

/**
 * Class Module
 *
 * @package Avisota\Contao\SubscriptionRecipient\DataContainer
 */
class Module
{
    static protected $instance;

    /**
     * @return Recipient
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Inject the email field, if it is not selected.
     *
     * @param array $recipientFields
     *
     * @return array
     */
    public function injectRequiredRecipientFields($recipientFields)
    {
        $recipientFields = deserialize($recipientFields, true);
        if (!in_array('email', $recipientFields)) {
            $recipientFields[] = 'email';
        }
        return $recipientFields;
    }

    public function onLoadCallback()
    {
        MetaPalettes::appendFields('tl_module', 'registration', 'config', array('avisota_selectable_lists'));
        MetaPalettes::appendFields('tl_module', 'personalData', 'config', array('avisota_selectable_lists'));
    }
}
