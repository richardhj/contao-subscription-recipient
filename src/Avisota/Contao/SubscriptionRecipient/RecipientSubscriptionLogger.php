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

use Avisota\Contao\Subscription\Event\ConfirmSubscriptionEvent;
use Avisota\Contao\Core\Event\CreateRecipientEvent;
use Avisota\Contao\Core\Event\RecipientEvent;
use Avisota\Contao\Core\Event\RemoveRecipientEvent;
use Avisota\Contao\Subscription\Event\SubscribeEvent;
use Avisota\Contao\Subscription\Event\UnsubscribeEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecipientSubscriptionLogger implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			CreateRecipientEvent::NAME               => 'create',
			RemoveRecipientEvent::NAME               => 'remove',
		);
	}

	/**
	 * @param RecipientEvent $event
	 */
	public function create(RecipientEvent $event)
	{
		/** @var LoggerInterface $logger */
		$logger = $GLOBALS['container']['avisota.logger.subscription'];

		$recipient = $event->getRecipient();

		$logger->info(
			sprintf(
				'Recipient %s was created',
				$recipient->getEmail()
			),
			array('recipient' => $recipient->toArray())
		);
	}

	/**
	 * @param RecipientEvent $event
	 */
	public function remove(RecipientEvent $event)
	{
		/** @var LoggerInterface $logger */
		$logger = $GLOBALS['container']['avisota.logger.subscription'];

		$recipient = $event->getRecipient();

		$logger->info(
			sprintf(
				'Recipient %s was deleted',
				$recipient->getEmail()
			),
			array('recipient' => $recipient->toArray())
		);
	}
}
