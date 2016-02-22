<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\SubscriptionRecipient\Module;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Message\Core\Renderer\MessageRendererInterface;
use Avisota\Contao\Subscription\SubscriptionManager;
use Avisota\Transport\TransportInterface;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use Haste\Form\Form;
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
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function compile()
    {
        global $TL_DCA;

        $mailingListIds  = deserialize($this->avisota_mailing_lists, true);
        $recipientFields = array('email');

        if ($this->avisota_unsubscribe_show_mailing_lists) {
            $recipientFields[] = 'mailingLists';
        }

        $TL_DCA['orm_avisota_recipient']['fields']['mailingLists']['options'] = $this->loadMailingListOptions(
            $mailingListIds
        );

        $values = array();

        if (\Input::get('avisota_subscription_email')) {
            $values['email'] = \Input::get('avisota_subscription_email');
        }

        $form = $this->createForm($recipientFields, $values);

        $this->validateFormAndSendMail($form);

        $template = new \TwigFrontendTemplate($this->avisota_unsubscribe_form_template);
        $form->addToTemplate($template);

        $this->Template->form = $template->parse();
    }

    /**
     * @param Form $form
     */
    protected function validateFormAndSendMail(Form $form)
    {
        if (!$form->validate()) {
            return;
        }

        global $container;

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        /** @var TransportInterface $transport */
        $transport = $container['avisota.transport.default'];

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
                function (Avisota\Contao\Entity\Subscription $subscription) use ($mailingListIds) {
                    return $subscription->getMailingList()
                           && in_array($subscription->getMailingList()->getId(), $mailingListIds);
                }
            );

            /** @var SubscriptionManager $subscriptionManager */
            $subscriptionManager = $container['avisota.subscription'];

            /** @var Subscription[] $subscriptions */
            $subscriptionManager->unsubscribe($subscriptions);

            \Session::getInstance()->set('AVISOTA_LAST_SUBSCRIPTIONS', $subscriptions);

            if (count($subscriptions)) {
                if ($this->avisota_unsubscribe_confirmation_message) {
                    $messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
                    $message           = $messageRepository->find($this->avisota_unsubscribe_confirmation_message);

                    if ($message) {
                        /** @var MessageRendererInterface $renderer */
                        $renderer = $container['avisota.message.renderer'];

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
                    $event = new GetPageDetailsEvent($this->avisota_unsubscribe_confirmation_page);
                    $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

                    $event = new GenerateFrontendUrlEvent($event->getPageDetails());
                    $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

                    $event = new RedirectEvent($event->getUrl());
                    $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
                }
            }

            $this->Template->subscriptions = $subscriptions;
        } else {
            $this->Template->subscriptions = array();
        }
    }
}
