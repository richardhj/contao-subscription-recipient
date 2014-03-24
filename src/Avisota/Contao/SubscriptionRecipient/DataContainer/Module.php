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

namespace Avisota\Contao\SubscriptionRecipient\DataContainer;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Subscription;
use Avisota\Contao\Subscription\SubscriptionManager;
use Contao\Doctrine\ORM\DataContainer\General\EntityModel;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\DecodePropertyValueForWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ModelToLabelEvent;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Module
{
	static protected $instance;

	/**
	 * @return Recipient
	 */
	static public function getInstance()
	{
		if (static::$instance === null) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Inject the email field, if it is not selected.
	 *
	 * @param array $recipientFields
	 */
	public function injectRequiredRecipientFields($recipientFields)
	{
		$recipientFields = deserialize($recipientFields, true);
		if (!in_array('email', $recipientFields)) {
			$recipientFields[] = 'email';
		}
		return $recipientFields;
	}

	public function onload_callback()
	{
		\MetaPalettes::appendFields('tl_module', 'registration', 'config', array('avisota_selectable_lists'));
		\MetaPalettes::appendFields('tl_module', 'personalData', 'config', array('avisota_selectable_lists'));
	}
}
