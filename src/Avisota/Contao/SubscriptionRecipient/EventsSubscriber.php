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

use Avisota\Contao\Core\DataContainer\OptionsBuilder;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Subscription\Event\PrepareSubscriptionEvent;
use Avisota\Contao\Subscription\Event\ResolveRecipientEvent;
use Avisota\Contao\Subscription\Event\SubscriptionAwareEvent;
use Avisota\Contao\Subscription\SubscriptionEvents;
use Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent;
use Contao\Doctrine\DBAL\DoctrineDbalEvents;
use Contao\Doctrine\DBAL\Event\InitializeEventManager;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetEditModeButtonsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
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
			SubscriptionEvents::UNSUBSCRIBE                                                                     => 'cleanRecipient',
			SubscriptionEvents::CLEAN_SUBSCRIPTION                                                              => 'cleanRecipient',
			SubscriptionEvents::PREPARE_SUBSCRIPTION                                                            => 'prepareSubscription',
			SubscriptionEvents::RESOLVE_RECIPIENT                                                               => 'resolveRecipient',
			SubscriptionEvents::CREATE_RECIPIENT_PROPERTIES_OPTIONS                                             => 'createRecipientPropertiesOptions',
			GetPropertyOptionsEvent::NAME . '[orm_avisota_recipient_source][recipientsPropertyFilter_property]' => 'bypassCreateRecipientPropertiesOptions',
			GetPropertyOptionsEvent::NAME . '[mem_avisota_recipient_migrate][mailingList]'                      => 'bypassCreateMailingListOptions',
			GetEditModeButtonsEvent::NAME . '[mem_avisota_recipient_migrate]' => 'getMigrateButtons',
			DoctrineDbalEvents::INITIALIZE_EVENT_MANAGER                                                        => 'initializeEventManager',
			RecipientEvents::MIGRATE_RECIPIENT                                                          => 'collectMemberPersonals',
		);
	}

	public function cleanRecipient(SubscriptionAwareEvent $event)
	{
		if (!$GLOBALS['TL_CONFIG']['avisota_subscription_recipient_cleanup']) {
			return;
		}

		$subscription = $event->getSubscription();
		$recipient    = $subscription->getRecipient();

		if ($recipient) {
			$subscriptions = $recipient->getSubscriptions();

			if (
				$subscriptions->isEmpty() ||
				$subscriptions->count() == 1 &&
				$subscriptions->contains($subscription)
			) {
				$entityManager = EntityHelper::getEntityManager();
				$entityManager->remove($recipient);
			}
		}
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
		$options = $event->getOptions();
		$this->getRecipientPropertiesOptions($event->getDataContainer()->getEnvironment(), $options);
	}

	public function bypassCreateRecipientPropertiesOptions(GetPropertyOptionsEvent $event)
	{
		$options = $event->getOptions();
		$options = $this->getRecipientPropertiesOptions($event->getEnvironment(), $options);
		$event->setOptions($options);
	}

	public function bypassCreateMailingListOptions(GetPropertyOptionsEvent $event)
	{
		/** @var OptionsBuilder $optionsBuilder */
		$optionsBuilder = $GLOBALS['container']['avisota.core.options-builder'];

		$options = $event->getOptions();
		$options = $optionsBuilder->getMailingListOptions($options);
		$event->setOptions($options);
	}

	public function getRecipientPropertiesOptions(EnvironmentInterface $environment, $options = array())
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$loadDataContainerEvent = new LoadDataContainerEvent('orm_avisota_recipient');
		$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER, $loadDataContainerEvent);

		$loadLanguageFileEvent = new LoadLanguageFileEvent('orm_avisota_recipient');
		$eventDispatcher->dispatch(ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE, $loadLanguageFileEvent);

		$dcGeneralFactory = DcGeneralFactory::deriveFromEnvironment($environment);

		$dcGeneralFactory->setContainerName('orm_avisota_recipient');
		$container  = $dcGeneralFactory->createContainer();
		$properties = $container->getPropertiesDefinition()->getProperties();

		foreach ($properties as $property) {
			$options[$property->getName()] = $property->getLabel() ? : $property->getName();
		}

		return $options;
	}

	public function getMigrateButtons(GetEditModeButtonsEvent $event)
	{
		$translator = $event->getEnvironment()->getTranslator();

		$buttons = array(
			'migrate' => sprintf(
				'<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="%s" />',
				$translator->translate('submit', 'mem_avisota_recipient_migrate')
			)
		);

		$event->setButtons($buttons);
	}

	public function initializeEventManager(InitializeEventManager $event)
	{
		$bridge = new DoctrineBridgeSubscriber($event->getDispatcher());

		$eventManager = $event->getEventManager();
		$eventManager->addEventSubscriber($bridge);
	}

	/**
	 * While migrate Contao newsletter recipients, fill with member details.
	 *
	 * @param MigrateRecipientEvent $event
	 */
	public function collectMemberPersonals(MigrateRecipientEvent $event)
	{
		$migrationSettings = $event->getMigrationSettings();

		if ($migrationSettings['importFromMembers']) {
			$recipient = $event->getRecipient();

			/** @var \Doctrine\DBAL\Connection $connection */
			$connection = $GLOBALS['container']['doctrine.connection.default'];

			$queryBuilder = $connection->createQueryBuilder();

			/** @var \PDOStatement $stmt */
			$stmt   = $queryBuilder
				->select('*')
				->from('tl_member', 'm')
				->where(
					$queryBuilder
						->expr()
						->eq('m.email', ':email')
				)
				->setParameter('email', $recipient->getEmail())
				->execute();
			$member = $stmt->fetch(\PDO::FETCH_ASSOC);

			if ($member) {
				/** @var EntityAccessor $entityAccessor */
				$entityAccessor = $GLOBALS['container']['doctrine.orm.entityAccessor'];

				foreach ($member as $key => $value) {
					// graceful conversions
					switch ($key) {
						case 'firstname':
							$key = 'forename';
							break;
						case 'lastname':
							$key = 'surname';
							break;
					}

					if ($entityAccessor->hasProperty($recipient, $key)) {
						$entityAccessor->setProperty($recipient, $key, $value);
					}
				}
			}
		}
	}
}
