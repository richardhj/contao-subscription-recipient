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

namespace Avisota\Contao\SubscriptionRecipient\DataContainer\DataProvider;

use Avisota\Contao\Subscription\SubscriptionManager;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent;
use Avisota\Contao\SubscriptionRecipient\RecipientEvents;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\Data\NoOpDataProvider;
use Doctrine\DBAL\Driver\PDOStatement;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RecipientMigrateDataProvider extends NoOpDataProvider
{
	/**
	 * {@inheritdoc}
	 */
	public function save(ModelInterface $objItem)
	{
		global $container;

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $container['event-dispatcher'];

		/** @var \Doctrine\DBAL\Connection $connection */
		$connection = $container['doctrine.connection.default'];

		$migrationSettings = $objItem->getPropertiesAsArray();

		$entityManager         = EntityHelper::getEntityManager();
		$recipientRepository   = EntityHelper::getRepository('Avisota\Contao:Recipient');
		$mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');

		$channels                  = array();
		$channelMailingListMapping = array();
		foreach ($migrationSettings['channels'] as $channel) {
			$mailingList                         = $channel['mailingList'];
			$channel                             = $channel['channel'];
			$channels[]                          = $connection->quote($channel);
			$channelMailingListMapping[$channel] = $mailingListRepository->find($mailingList);
		}

		$queryBuilder = $connection->createQueryBuilder();
		/** @var PDOStatement $stmt */
		$stmt = $queryBuilder
			->select('*')
			->from('tl_newsletter_recipients', 'r')
			->where(
				$queryBuilder
					->expr()
					->in('pid', $channels)
			)
			->execute();

		/** @var SubscriptionManager $subscriptionManager */
		$subscriptionManager = $container['avisota.subscription'];
		$subscribeOptions    = 0;

		if ($migrationSettings['ignoreBlacklist']) {
			$subscribeOptions |= SubscriptionManager::OPT_IGNORE_BLACKLIST;
		}

		$user = \BackendUser::getInstance();

		$skipped  = 0;
		$migrated = 0;

		$contaoRecipients = $stmt->fetchAll();
		foreach ($contaoRecipients as $contaoRecipientData) {
			$recipient = $recipientRepository->findOneBy(array('email' => $contaoRecipientData['email']));

			if (!$recipient) {
				$recipient = new Recipient();
				$recipient->setEmail($contaoRecipientData['email']);
				$recipient->setAddedById($user->id);
				$recipient->setAddedByName($user->name);
				$recipient->setAddedByUsername($user->username);
			}
			else if (!$migrationSettings['overwrite']) {
				$skipped++;
				continue;
			}

			$mailingList = $channelMailingListMapping[$contaoRecipientData['pid']];

			if (!$mailingList) {
				// graceful ignore missing mailing lists
				$skipped++;
				continue;
			}

			$event = new MigrateRecipientEvent($migrationSettings, $contaoRecipientData, $recipient);
			$eventDispatcher->dispatch(RecipientEvents::MIGRATE_RECIPIENT, $event);

			$entityManager->persist($recipient);

			$subscriptionManager->subscribe(
				$recipient,
				$mailingList,
				($contaoRecipientData['active'] ? SubscriptionManager::OPT_ACTIVATE : 0) | $subscribeOptions
			);

			$migrated++;
		}
		$entityManager->flush();

		if (!is_array($_SESSION['TL_CONFIRM'])) {
			$_SESSION['TL_CONFIRM'] = (array) $_SESSION['TL_CONFIRM'];
		}
		$_SESSION['TL_CONFIRM'][] = sprintf(
			$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['migrated'],
			$migrated,
			$skipped
		);
	}
}
