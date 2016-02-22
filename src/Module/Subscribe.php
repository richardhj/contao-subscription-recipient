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
use Avisota\Contao\Entity\Subscription;
use Avisota\Contao\Message\Core\Renderer\MessageRendererInterface;
use Avisota\Contao\Subscription\SubscriptionManager;
use Avisota\Transport\TransportInterface;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\EntityHelper;
use Contao\Doctrine\ORM\Exception\UnknownPropertyException;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Subscribe
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Subscribe extends AbstractRecipientForm
{
    protected $strTemplate = 'avisota/subscription-recipient/mod_avisota_subscribe';

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

        return parent::generate();
    }

    /**
     * Generate the content element
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function compile()
    {
        global $TL_DCA;

        $this->parseConfirmed();

        $mailingListIds  = deserialize($this->avisota_mailing_lists, true);
        $recipientFields = array_merge(array('email'), deserialize($this->avisota_recipient_fields, true));

        $TL_DCA['orm_avisota_recipient']['fields']['mailingLists']['options'] = $this->loadMailingListOptions(
            $mailingListIds
        );

        $values = array();

        if (\Input::get('avisota_subscription_email')) {
            $values['email'] = \Input::get('avisota_subscription_email');
        }

        $form = $this->createForm($recipientFields, $values);

        $this->parseSubscriptions($form, $recipientFields, $mailingListIds);

        $template = new \TwigFrontendTemplate($this->avisota_subscribe_form_template);
        $form->addToTemplate($template);

        $this->Template->form = $template->parse();
    }

    /**
     * parse the confirmed subscriptions for the template
     */
    protected function parseConfirmed()
    {
        $token = (array) \Input::get('token');

        if (empty($token)) {
            return;
        }

        global $container;

        /** @var SubscriptionManager $subscriptionManager */
        $subscriptionManager = $container['avisota.subscription'];

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $subscriptions = $subscriptionManager->confirmByToken($token);

        \Session::getInstance()->set('AVISOTA_LAST_SUBSCRIPTIONS', $subscriptions);

        if ($this->avisota_subscribe_activate_confirmation_page) {
            $event = new GetPageDetailsEvent($this->avisota_subscribe_activate_confirmation_page);
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

            $event = new GenerateFrontendUrlEvent($event->getPageDetails());
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

            $event = new RedirectEvent($event->getUrl());
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
        }

        $this->Template->confirmed = $subscriptions;
    }

    /**
     * @param $form
     * @param $recipientFields
     * @param $mailingListIds
     */
    protected function parseSubscriptions($form, $recipientFields, $mailingListIds)
    {
        if (!$form->validate()) {
            return;
        }

        global $container;

        /** @var SubscriptionManager $subscriptionManager */
        $subscriptionManager = $container['avisota.subscription'];

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $values     = $form->fetchAll();
        $email      = $values['email'];
        $repository = EntityHelper::getRepository('Avisota\Contao:Recipient');
        $recipient  = $repository->findOneBy(array('email' => $email));

        if (!$recipient) {
            $recipient = new Recipient();
        }

        $this->setPropertiesToRecipient($recipient, $values);

        $entityManager = EntityHelper::getEntityManager();
        $entityManager->persist($recipient);

        if (in_array('mailingLists', $recipientFields)) {
            $mailingLists = $this->loadMailingLists($values['mailingLists']);
        } else {
            $mailingLists = $this->loadMailingLists($mailingListIds);
        }

        $subscriptions = $subscriptionManager->subscribe(
            $recipient,
            $mailingLists,
            SubscriptionManager::OPT_IGNORE_BLACKLIST | SubscriptionManager::OPT_INCLUDE_EXISTING
        );

        $subscriptions = array_filter(
            $subscriptions,
            function (Subscription $subscription) {
                return !$subscription->getActive();
            }
        );

        /** @var Subscription[] $subscriptions */
        \Session::getInstance()->set('AVISOTA_LAST_SUBSCRIPTIONS', $subscriptions);

        $entityManager->flush();

        if (count($subscriptions)) {
            $this->subscribeConfirmationMessage($subscriptions, $recipient);

            if ($this->avisota_subscribe_confirmation_page) {
                $event = new GetPageDetailsEvent($this->avisota_subscribe_confirmation_page);
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

                $event = new GenerateFrontendUrlEvent($event->getPageDetails());
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

                $event = new RedirectEvent($event->getUrl());
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
            }
        }

        $this->Template->subscriptions = $subscriptions;
    }

    /**
     * @param $subscriptions
     * @param $recipient
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function subscribeConfirmationMessage($subscriptions, $recipient)
    {
        if (!$this->avisota_subscribe_confirmation_message) {
            return;
        }

        global $container,
               $objPage,
               $TL_LANG;

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        /** @var TransportInterface $transport */
        $transport = $container['avisota.transport.default'];

        $messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
        $message           = $messageRepository->find($this->avisota_subscribe_confirmation_message);

        if ($message) {
            /** @var MessageRendererInterface $renderer */
            $renderer = $container['avisota.message.renderer'];

            $pageDetails = $objPage->row();
            if ($this->avisota_subscribe_activation_page) {
                $event = new GetPageDetailsEvent($this->avisota_subscribe_activation_page);
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

                $pageDetails = $event->getPageDetails();
            }

            $event = new GenerateFrontendUrlEvent($pageDetails);
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

            $query = array('token' => array());

            foreach ($subscriptions as $subscription) {
                $query['token'][] = $subscription->getActivationToken();
            }

            $base = \Environment::get('base');
            $url  = $base . $event->getUrl() . '?' . http_build_query($query);

            $data = array(
                'link'          => array(
                    'url'  => $url,
                    'text' => $TL_LANG['fe_avisota_subscription']['confirm'],
                ),
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

    /**
     * @param Recipient $recipient
     * @param           $properties
     */
    protected function setPropertiesToRecipient(Recipient &$recipient, $properties)
    {
        global $container;

        /** @var EntityAccessor $entityAccessor */
        $entityAccessor = $container['doctrine.orm.entityAccessor'];

        foreach ($properties as $propertyName => $propertyValue) {
            if ($propertyName != 'submit' && $propertyName != 'mailingLists') {
                try {
                    $entityAccessor->setProperty($recipient, $propertyName, $propertyValue);
                } catch (UnknownPropertyException $e) {
                    // gracefully ignore non-public properties
                }
            }
        }
    }
}
