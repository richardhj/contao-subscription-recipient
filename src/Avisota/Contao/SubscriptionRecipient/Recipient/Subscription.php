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

namespace Avisota\Contao\SubscriptionRecipient\Recipient;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Subscription\Event\CollectSubscriptionListsEvent;
use Contao\Doctrine\ORM\EntityHelper;

class Subscription extends \Controller
{
	static public function collectSubscriptionLists(CollectSubscriptionListsEvent $event)
	{
		$mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
		/** @var MailingList[] $mailingLists */
		$mailingLists = $mailingListRepository->findAll();

		$mailingListOptions = array();
		foreach ($mailingLists as $mailingList) {
			$mailingListOptions['mailing_list:' . $mailingList->id()] = $mailingList->getTitle();
		}

		$options = $event->getOptions();
		$options['mailing_list'] = $mailingListOptions;
	}
}
