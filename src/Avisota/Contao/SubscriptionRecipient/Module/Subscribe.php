<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2015 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
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
        global $TL_DCA,
               $TL_LANG,
               $container,
               $objPage;



        /** @var SubscriptionManager $subscriptionManager */
        $subscriptionManager = $container['avisota.subscription'];

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $token = (array) \Input::get('token');

        if (count($token)) {
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

        if ($form->validate()) {
            /** @var EntityAccessor $entityAccessor */
            $entityAccessor = $container['doctrine.orm.entityAccessor'];

            /** @var TransportInterface $transport */
            $transport = $container['avisota.transport.default'];

            $values     = $form->fetchAll();
            $email      = $values['email'];
            $repository = EntityHelper::getRepository('Avisota\Contao:Recipient');
            $recipient  = $repository->findOneBy(array('email' => $email));

            if (!$recipient) {
                $recipient = new Recipient();
            }

            foreach ($values as $propertyName => $value) {
                if ($propertyName != 'submit' && $propertyName != 'mailingLists') {
                    try {
                        $entityAccessor->setProperty($recipient, $propertyName, $value);
                    } catch (UnknownPropertyException $e) {
                        // gracefully ignore non-public properties
                    }
                }
            }

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
                if ($this->avisota_subscribe_confirmation_message) {
                    $messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
                    $message           = $messageRepository->find($this->avisota_subscribe_confirmation_message);

                    if ($message) {
                        /** @var MessageRendererInterface $renderer */
                        $renderer = $container['avisota.message.renderer'];

                        if ($this->avisota_subscribe_activation_page) {
                            $event = new GetPageDetailsEvent($this->avisota_subscribe_activation_page);
                            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

                            $pageDetails = $event->getPageDetails();
                        } else {
                            $pageDetails = $objPage->row();
                        }

                        $event = new GenerateFrontendUrlEvent($pageDetails);
                        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

                        $query = array('token' => array());

                        foreach ($subscriptions as $subscription) {
                            $query['token'][] = $subscription->getActivationToken();
                        }


                        $base        = \Environment::get('base');
                        $url         = $base . $event->getUrl() . '?' . http_build_query($query);

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

        $template = new \TwigFrontendTemplate($this->avisota_subscribe_form_template);
        $form->addToTemplate($template);

        $this->Template->form = $template->parse();
    }
}
