<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\SubscriptionRecipient;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Subscription\Event\PrepareSubscriptionEvent;
use Avisota\Contao\Subscription\Event\ResolveRecipientEvent;
use Avisota\Contao\Subscription\SubscriptionEvents;
use Contao\Doctrine\ORM\EntityHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RecipientEvents
 */
class RecipientEvents
{
	/**
	 * The CREATE_RECIPIENT event occurs when a recipient is created.
	 *
	 * This event allows you to manipulate the recipient, after it is added to the database. The event listener
	 * method receives a Avisota\Contao\SubscriptionRecipient\Event\RecipientAwareEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_RECIPIENT = 'avisota.subscription-recipient.create-recipient';

	/**
	 * The UPDATE_RECIPIENT event occurs when a recipient is updated.
	 *
	 * This event allows you to manipulate the recipient, after it is updated in the database. The event listener
	 * method receives a Avisota\Contao\SubscriptionRecipient\Event\RecipientAwareEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const UPDATE_RECIPIENT = 'avisota.subscription-recipient.update-recipient';

	/**
	 * The REMOVE_RECIPIENT event occurs when a recipient is created.
	 *
	 * This event allows you to handle the recipient, after it has been removed from the database. The event
	 * listener method receives a Avisota\Contao\SubscriptionRecipient\Event\RecipientAwareEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const REMOVE_RECIPIENT = 'avisota.subscription-recipient.remove-recipient';
}
