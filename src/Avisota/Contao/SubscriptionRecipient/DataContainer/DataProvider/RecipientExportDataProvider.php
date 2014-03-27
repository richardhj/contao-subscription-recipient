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
use Avisota\Contao\SubscriptionRecipient\Event\ExportRecipientPropertyEvent;
use Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent;
use Avisota\Contao\SubscriptionRecipient\RecipientEvents;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\DcGeneral\Data\ConfigInterface;
use ContaoCommunityAlliance\DcGeneral\Data\DefaultCollection;
use ContaoCommunityAlliance\DcGeneral\Data\DefaultModel;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\Data\NoOpDataProvider;
use Doctrine\DBAL\Driver\PDOStatement;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RecipientExportDataProvider extends NoOpDataProvider
{
	const SESSION_NAME = 'AVISOTA_RECIPIENT_EXPORT_SETTINGS';

	/**
	 * {@inheritdoc}
	 */
	public function save(ModelInterface $objItem)
	{
		global $container;

		/** @var EntityAccessor $entityAccessor */
		$entityAccessor = $GLOBALS['container']['doctrine.orm.entityAccessor'];

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $container['event-dispatcher'];

		$exportSettings = $objItem->getPropertiesAsArray();

		$session = \Session::getInstance();
		$recipientRepository   = EntityHelper::getRepository('Avisota\Contao:Recipient');

		$session->set(static::SESSION_NAME, $exportSettings);

		$propertyNames = $exportSettings['columns'];

		switch ($exportSettings['delimiter']) {
			case 'semicolon':
				$delimiter = ';';
				break;
			case 'space':
				$delimiter = ' ';
				break;
			case 'tabulator':
				$delimiter = "\t";
				break;
			case 'linebreak':
				$delimiter = "\n";
				break;
			default:
				$delimiter = ',';
		}

		switch ($exportSettings['enclosure']) {
			case 'single':
				$enclosure = "'";
				break;
			default:
				$enclosure = '"';
		}

		$length = 0;
		$csv = tmpfile();
		$recipients = $recipientRepository->findAll();

		/** @var Recipient $recipient */
		foreach ($recipients as $recipient) {
			$row = array();

			foreach ($propertyNames as $propertyName) {
				if ($entityAccessor->hasProperty($recipient, $propertyName)) {
					$value = $entityAccessor->getProperty($recipient, $propertyName);
				}
				else {
					$value = null;
				}

				if (is_resource($value)) {
					$string = stream_get_contents($value);
				}
				else if (is_object($value)) {
					if (method_exists($value, '__toString')) {
						$string = (string) $value;
					}
				}
				else if (is_scalar($value)) {
					$string = (string) $value;
				}
				else {
					$string = null;
				}

				$event = new ExportRecipientPropertyEvent($recipient, $propertyName, $value, $string);
				$eventDispatcher->dispatch(RecipientEvents::EXPORT_RECIPIENT_PROPERTY, $event);

				$row[] = $event->getString();
			}

			$length += fputcsv($csv, $row, $delimiter, $enclosure);
		}

		if (!headers_sent()) {
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Length: ' . $length);
			header('Content-Disposition: attachment; filename="export.csv"');
		}

		rewind($csv);
		fpassthru($csv);
		fclose($csv);
		exit;
	}

	public function getEmptyModel()
	{
		$session = \Session::getInstance();
		$exportSettings = $session->get(static::SESSION_NAME);

		$model = parent::getEmptyModel();

		if ($exportSettings && is_array($exportSettings)) {
			$model->setPropertiesAsArray($exportSettings);
		}

		return $model;
	}
}