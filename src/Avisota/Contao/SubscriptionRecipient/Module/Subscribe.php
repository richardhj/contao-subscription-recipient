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

namespace Avisota\Contao\SubscriptionRecipient\Module;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ModuleAvisotaSubscribe
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-subscription-recipient
 */
class Subscribe extends AbstractRecipientForm
{
	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_subscribe';

	public function __construct($module)
	{
		parent::__construct($module);

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$eventDispatcher->dispatch(
			ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
			new LoadLanguageFileEvent('avisota_subscription')
		);
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$template           = new \BackendTemplate('be_wildcard');
			$template->wildcard = '### Avisota subscribe module ###';
			return $template->parse();
		}

		$this->formTemplate = $this->avisota_template_subscribe;

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	public function compile()
	{
		$this->addForm();
	}

	protected function submit(array $recipientData, array $mailingLists, \TwigFrontendTemplate $template)
	{
		return $this->handleSubscribeSubmit(
			$recipientData,
			$mailingLists,
			$this->avisota_subscribe_mail,
			$this->avisota_transport
		);
	}
}
