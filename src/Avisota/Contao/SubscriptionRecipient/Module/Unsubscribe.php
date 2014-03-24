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

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\Subscription;
use Avisota\Contao\Message\Core\Renderer\MessageRendererInterface;
use Avisota\Contao\Subscription\SubscriptionManager;
use Avisota\Transport\TransportInterface;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\EntityHelper;
use Contao\Doctrine\ORM\Exception\UnknownPropertyException;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Unsubscribe
 */
class Unsubscribe extends AbstractRecipientForm
{
	protected $strTemplate = 'avisota/subscription-recipient/mod_avisota_unsubscribe';

	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$template           = new \BackendTemplate('be_wildcard');
			$template->wildcard = '### Avisota unsubscribe module ###';
			return $template->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	public function compile()
	{
		/** @var SubscriptionManager $subscriptionManager */
		$subscriptionManager = $GLOBALS['container']['avisota.subscription'];

		$mailingListIds  = deserialize($this->avisota_mailing_lists, true);
		$recipientFields = array('email');

		if ($this->avisota_unsubscribe_show_mailing_lists) {
			$recipientFields[] = 'mailingLists';
		}

		$GLOBALS['TL_DCA']['orm_avisota_recipient']['fields']['mailingLists']['options'] = $this->loadMailingListOptions(
			$mailingListIds
		);

		$form = $this->createForm($recipientFields);

		if ($form->validate()) {
			/** @var EventDispatcher $eventDispatcher */
			$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

			/** @var TransportInterface $transport */
			$transport = $GLOBALS['container']['avisota.transport.default'];

			$values     = $form->fetchAll();
			$email      = $values['email'];
			$repository = EntityHelper::getRepository('Avisota\Contao:Recipient');
			$recipient  = $repository->findOneBy(array('email' => $email));

			if ($recipient) {
				/** @var Recipient $recipient */

				if ($this->avisota_unsubscribe_show_mailing_lists) {
					$mailingListIds = $values['mailingLists'];
				}

				$subscriptions = $recipient->getSubscriptions();

				$subscriptions = array_filter(
					$subscriptions->toArray(),
					function (Subscription $subscription) use ($mailingListIds) {
						return $subscription->getMailingList() && in_array($subscription->getMailingList()->getId(), $mailingListIds);
					}
				);

				/** @var Subscription[] $subscriptions */

				$subscriptionManager->unsubscribe($subscriptions);

				$_SESSION['AVISOTA_LAST_SUBSCRIPTIONS'] = $subscriptions;

				if (count($subscriptions)) {
					if ($this->avisota_unsubscribe_confirmation_message) {
						$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
						$message           = $messageRepository->find($this->avisota_unsubscribe_confirmation_message);

						if ($message) {
							/** @var MessageRendererInterface $renderer */
							$renderer = $GLOBALS['container']['avisota.message.renderer'];

							$data = array(
								'subscriptions' => $subscriptions,
							);

							$template = $renderer->renderMessage($message);

							$mail = $template->render(
								$recipient,
								$data
							);

							$transport->send($mail);
						}
					}

					if ($this->avisota_unsubscribe_confirmation_page) {
						$event = new GetPageDetailsEvents($this->avisota_unsubscribe_confirmation_page);
						$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

						$event = new GenerateFrontendUrlEvent($event->getPageDetails());
						$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

						$event = new RedirectEvent($event->getUrl());
						$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
					}
				}

				$this->Template->subscriptions = $subscriptions;
			}
			else {
				$this->Template->subscriptions = array();
			}
		}

		$template = new \TwigFrontendTemplate($this->avisota_unsubscribe_form_template);
		$form->addToTemplate($template);

		$this->Template->form = $template->parse();
	}
}
