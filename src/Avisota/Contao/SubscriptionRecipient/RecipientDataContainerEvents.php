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
 * Class RecipientDataContainerEvents
 */
class RecipientDataContainerEvents
{
	/**
	 * The CREATE_IMPORTABLE_RECIPIENT_FIELD_OPTIONS event occurs when an
	 * options list of importable recipient field options will be created.
	 *
	 * The event listener method receives a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_IMPORTABLE_RECIPIENT_FIELD_OPTIONS = 'avisota.subscription-recipient.create-importable-recipient-field-options';

	/**
	 * The CREATE_EDITABLE_RECIPIENT_FIELD_OPTIONS event occurs when an
	 * options list of editable recipient field options will be created.
	 *
	 * The event listener method receives a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_EDITABLE_RECIPIENT_FIELD_OPTIONS = 'avisota.subscription-recipient.create-editable-recipient-field-options';

	/**
	 * The CREATE_SUBSCRIBE_TEMPLATE_OPTIONS event occurs when an options list of subscribe templates will be created.
	 *
	 * The event listener method receives a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_SUBSCRIBE_TEMPLATE_OPTIONS = 'avisota.subscription-recipient.create-subscribe-template-options';

	/**
	 * The CREATE_UNSUBSCRIBE_TEMPLATE_OPTIONS event occurs when an options list of unsubscribe templates will be created.
	 *
	 * The event listener method receives a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_UNSUBSCRIBE_TEMPLATE_OPTIONS = 'avisota.subscription-recipient.create-unsubscribe-template-options';

	/**
	 * The CREATE_SUBSCRIPTION_TEMPLATE_OPTIONS event occurs when an options list of subscription templates will be created.
	 *
	 * The event listener method receives a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_SUBSCRIPTION_TEMPLATE_OPTIONS = 'avisota.subscription-recipient.create-subscription-template-options';
}
