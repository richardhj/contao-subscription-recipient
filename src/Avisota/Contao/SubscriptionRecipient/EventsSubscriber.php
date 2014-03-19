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
use Contao\Doctrine\DBAL\DoctrineDbalEvents;
use Contao\Doctrine\DBAL\Event\InitializeEventManager;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventHelper;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\DC_General;
use ContaoCommunityAlliance\DcGeneral\DcGeneral;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Factory\DcGeneralFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EventsSubscriber
 */
class EventsSubscriber implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			SubscriptionEvents::PREPARE_SUBSCRIPTION     => 'prepareSubscription',
			SubscriptionEvents::RESOLVE_RECIPIENT        => 'resolveRecipient',
			'avisota.subscription-recipient.create-recipient-properties-options' => 'createRecipientPropertiesOptions',
			DoctrineDbalEvents::INITIALIZE_EVENT_MANAGER => 'initializeEventManager',
		);
	}

	public function prepareSubscription(PrepareSubscriptionEvent $event)
	{
		if ($event->getRecipient() instanceof Recipient) {
			$event->getSubscription()->setRecipient($event->getRecipient());
		}
	}

	public function resolveRecipient(ResolveRecipientEvent $event)
	{
		// some other event listener has already resolved the recipient
		if ($event->getRecipient()) {
			return;
		}

		$subscription = $event->getSubscription();

		if ($subscription->getRecipientType() == 'Avisota\Contao\Entity\Recipient') {
			$entityManager = EntityHelper::getEntityManager();
			$repository    = $entityManager->getRepository('Avisota\Contao\Entity\Recipient');
			$recipient     = $repository->find($subscription->getRecipientId());
			$event->setRecipient($recipient);
		}
	}

	public function createRecipientPropertiesOptions(CreateOptionsEvent $event)
	{
		$loadDataContainerEvent = new LoadDataContainerEvent('orm_avisota_recipient');

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];
		$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER, $loadDataContainerEvent);

		$options = $event->getOptions();

		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $fieldName => $fieldConfig) {
			$options[$fieldName] = $fieldConfig['label'][0];
		}
	}

	public function initializeEventManager(InitializeEventManager $event)
	{
		$bridge = new DoctrineBridgeSubscriber($event->getDispatcher());

		$eventManager = $event->getEventManager();
		$eventManager->addEventSubscriber($bridge);
	}
}
