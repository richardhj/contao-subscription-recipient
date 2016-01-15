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

namespace Avisota\Contao\SubscriptionRecipient\Event;

/**
 * Class CreateRecipientEvent
 *
 * @package Avisota\Contao\SubscriptionRecipient\Event
 */
class CreateRecipientEvent extends RecipientAwareEvent
{
    const NAME = 'Avisota\Contao\Core\Event\CreateRecipient';
}
