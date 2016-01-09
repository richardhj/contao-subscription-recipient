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

/**
 * Class RemoveRecipientEvent
 *
 * @package Avisota\Contao\SubscriptionRecipient\Event
 */
class RemoveRecipientEvent extends RecipientAwareEvent
{
    const NAME = 'Avisota\Contao\Core\Event\RemoveRecipient';
}
