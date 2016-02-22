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

use Avisota\Contao\Subscription\SubscriptionManager;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Activation
 */
class Activation extends AbstractRecipientForm
{
    protected $strTemplate = 'avisota/subscription-recipient/mod_avisota_activation';

    /**
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template           = new \BackendTemplate('be_wildcard');
            $template->wildcard = '### Avisota activation module ###';
            return $template->parse();
        }

        return parent::generate();
    }

    /**
     * Generate the content element
     */
    public function compile()
    {
        global $container;

        /** @var SubscriptionManager $subscriptionManager */
        $subscriptionManager = $container['avisota.subscription'];

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $token = (array) \Input::get('token');

        if (count($token)) {
            $subscriptions = $subscriptionManager->confirmByToken($token);

            \Session::getInstance()->set('AVISOTA_LAST_SUBSCRIPTIONS', $subscriptions);

            if ($this->avisota_activation_confirmation_page) {
                $event = new GetPageDetailsEvent($this->avisota_activation_confirmation_page);
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

                $event = new GenerateFrontendUrlEvent($event->getPageDetails());
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

                $event = new RedirectEvent($event->getUrl());
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
            }

            $this->Template->confirmed = $subscriptions;
        } else {
            if ($this->avisota_activation_redirect_page) {
                $event = new GetPageDetailsEvent($this->avisota_activation_redirect_page);
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

                $event = new GenerateFrontendUrlEvent($event->getPageDetails());
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

                $event = new RedirectEvent($event->getUrl());
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
            }
        }
    }
}