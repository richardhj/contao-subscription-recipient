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

namespace Avisota\Contao\SubscriptionRecipient\Event;

use Avisota\Contao\Entity\Recipient;
use Symfony\Component\EventDispatcher\Event;

class RecipientMigrateCollectPersonalsAwareEvent extends RecipientAwareEvent
{
	const NAME = 'Avisota\Contao\Core\Event\RecipientMigrateCollectPersonals';

	/**
	 * @var array
	 */
	protected $migrationSettings;

	/**
	 * @var array
	 */
	protected $contaoRecipientData;

	function __construct(array $migrationSettings, array $contaoRecipientData, Recipient $recipient)
	{
		parent::__construct($recipient);
		$this->migrationSettings = $migrationSettings;
		$this->contaoRecipientData = $contaoRecipientData;
	}

	/**
	 * @return array
	 */
	public function getMigrationSettings()
	{
		return $this->migrationSettings;
	}

	/**
	 * @return array
	 */
	public function getContaoRecipientData()
	{
		return $this->contaoRecipientData;
	}
}