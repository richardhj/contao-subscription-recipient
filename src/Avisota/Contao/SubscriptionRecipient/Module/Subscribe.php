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
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Haste\Form\Form;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ModuleAvisotaSubscribe
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-subscription-recipient
 */
class Subscribe extends \TwigModule
{
	public function __construct($module)
	{
		parent::__construct($module);

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$eventDispatcher->dispatch(
			ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
			new LoadLanguageFileEvent('fe_avisota_subscription')
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

		$this->strTemplate = $this->avisota_template_subscribe;

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	public function compile()
	{
		$recipientFields = deserialize($this->avisota_recipient_fields, true);

		$form = new Form(
			'avisota_subscribe_' . $this->id,
			'POST',
			function (Form $haste) {
				return \Input::post('FORM_SUBMIT') === $haste->getFormId();
			}
		);
		$form->addFieldsFromDca(
			'orm_avisota_recipient',
			function ($fieldName) use ($recipientFields) {
				return!in_array($fieldName, $recipientFields);
			}
		);

		if ($this->avisota_form_target) {
			$form->setFormActionFromPageId($this->avisota_form_target);
		}

		$form->addSubmitFormField('submit', $GLOBALS['TL_LANG']['fe_avisota_subscription']['subscribe']);

		if ($form->validate()) {
			var_dump($form->fetchAll());
			exit;
		}

		$form->addToTemplate($this->Template);
	}
}
