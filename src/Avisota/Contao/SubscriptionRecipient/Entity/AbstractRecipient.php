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

namespace Avisota\Contao\SubscriptionRecipient\Entity;

use Avisota\Contao\Subscription\SubscriptionRecipientInterface;
use Contao\Doctrine\ORM\EntityAccessor;

abstract class AbstractRecipient implements SubscriptionRecipientInterface
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = strtolower($email);
		$this->email = \Contao\Doctrine\ORM\EntityHelper::callSetterCallbacks($this, self::TABLE_NAME, 'email', $email);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasDetails()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($name)
	{
		$getter = 'get' . ucfirst($name);
		return $this->$getter();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDetails()
	{
		/** @var EntityAccessor $entityAccessor */
		$entityAccessor = $GLOBALS['container']['doctrine.orm.entityAccessor'];
		$details = $entityAccessor->getPublicProperties($this, true);
		return $details;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKeys()
	{
		return array_keys($this->getDetails());
	}
}
