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
use Symfony\Component\EventDispatcher\Event;

class RecipientAwareEvent extends Event
{
    /**
     * @var Recipient
     */
    protected $recipient;

    function __construct(Recipient $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return Recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
}
