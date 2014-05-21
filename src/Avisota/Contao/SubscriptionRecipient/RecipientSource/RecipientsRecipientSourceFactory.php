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

namespace Avisota\Contao\SubscriptionRecipient\RecipientSource;

use Avisota\Contao\Core\CoreEvents;
use Avisota\Contao\Core\Event\CreateRecipientSourceEvent;
use Avisota\Contao\Core\RecipientSource\RecipientSourceFactoryInterface;
use Avisota\Contao\Entity\RecipientSource;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RecipientsRecipientSourceFactory implements RecipientSourceFactoryInterface
{
	public function createRecipientSource(RecipientSource $recipientSourceEntity)
	{
		$recipientSource = new RecipientsRecipientSource();

		if ($recipientSourceEntity->getFilterByMailingLists()) {
			$recipientSource->setFilteredMailingLists($recipientSourceEntity->getMailingLists()->toArray());
		}
		if ($recipientSourceEntity->getRecipientsUsePropertyFilter()) {
			$recipientSource->setFilteredProperties($recipientSourceEntity->getRecipientsPropertyFilter());
		}

		/** @var EventDispatcherInterface $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$event = new CreateRecipientSourceEvent($recipientSourceEntity, $recipientSource);
		$eventDispatcher->dispatch(CoreEvents::CREATE_RECIPIENT_SOURCE, $event);

		return $event->getRecipientSource();
	}
}
