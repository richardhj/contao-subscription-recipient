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

namespace Avisota\Contao\SubscriptionRecipient\Event;

use Avisota\Contao\Entity\Recipient;

/**
 * Class ExportRecipientPropertyEvent
 *
 * @package Avisota\Contao\SubscriptionRecipient\Event
 */
class ExportRecipientPropertyEvent extends RecipientAwareEvent
{
    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var mixed
     */
    protected $propertyValue;

    /**
     * @var string
     */
    protected $string;

    /**
     * @param Recipient $recipient
     * @param string    $propertyName
     * @param mixed     $propertyValue
     * @param null      $string
     */
    public function __construct(Recipient $recipient, $propertyName, $propertyValue = null, $string = null)
    {
        parent::__construct($recipient);
        $this->propertyName  = (string) $propertyName;
        $this->propertyValue = $propertyValue;
        $this->string        = $string === null ? null : (string) $string;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @param mixed $propertyValue
     *
     * @return $this
     */
    public function setPropertyValue($propertyValue)
    {
        $this->propertyValue = $propertyValue;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPropertyValue()
    {
        return $this->propertyValue;
    }

    /**
     * @param string $string
     *
     * @return $this
     */
    public function setString($string)
    {
        $this->string = $string === null ? null : (string) $string;
        return $this;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }
}
