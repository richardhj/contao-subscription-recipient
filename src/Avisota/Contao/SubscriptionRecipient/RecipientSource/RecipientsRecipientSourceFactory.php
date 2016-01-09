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

namespace Avisota\Contao\SubscriptionRecipient\RecipientSource;

use Avisota\Contao\Core\CoreEvents;
use Avisota\Contao\Core\Event\CreateRecipientSourceEvent;
use Avisota\Contao\Core\RecipientSource\RecipientSourceFactoryInterface;
use Avisota\Contao\Entity\RecipientSource;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class RecipientsRecipientSourceFactory
 *
 * @package Avisota\Contao\SubscriptionRecipient\RecipientSource
 */
class RecipientsRecipientSourceFactory implements RecipientSourceFactoryInterface
{
    /**
     * @param RecipientSource $entity
     *
     * @return mixed
     */
    public function createRecipientSource(RecipientSource $entity)
    {
        global $container;

        $recipientSource = new RecipientsRecipientSource();

        if ($entity->getFilter()) {
            if ($entity->getFilterByMailingLists()) {
                $recipientSource->setFilteredMailingLists($entity->getMailingLists()->toArray());
            }
            if ($entity->getRecipientsUsePropertyFilter()) {
                $recipientSource->setFilteredProperties($entity->getRecipientsPropertyFilter());
            }
        }

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        if ($entity->getRecipientsManageSubscriptionPage()) {
            $getPageDetailsEvent =
                new GetPageDetailsEvent($entity->getRecipientsManageSubscriptionPage());
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $getPageDetailsEvent);

            $generateFrontendUrlEvent = new GenerateFrontendUrlEvent($getPageDetailsEvent->getPageDetails());
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $generateFrontendUrlEvent);

            $url = $generateFrontendUrlEvent->getUrl();
            $url .= (strpos($url, '?') !== false ? '&' : '?') . 'avisota_subscription_email=##email##';

            if (!preg_match('~^\w+:~', $url)) {
                $environment = \Environment::getInstance();
                $url         = rtrim($environment->base, '/') . '/' . ltrim($url, '/');
            }

            $recipientSource->setManageSubscriptionUrlPattern($url);
        }

        if ($entity->getRecipientsUnsubscribePage()) {
            $getPageDetailsEvent = new GetPageDetailsEvent($entity->getRecipientsUnsubscribePage());
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $getPageDetailsEvent);

            $generateFrontendUrlEvent = new GenerateFrontendUrlEvent($getPageDetailsEvent->getPageDetails());
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $generateFrontendUrlEvent);

            $url = $generateFrontendUrlEvent->getUrl();
            $url .= (strpos($url, '?') !== false ? '&' : '?') . 'avisota_subscription_email=##email##';

            if (!preg_match('~^\w+:~', $url)) {
                $environment = \Environment::getInstance();
                $url         = rtrim($environment->base, '/') . '/' . ltrim($url, '/');
            }

            $recipientSource->setUnsubscribeUrlPattern($url);
        }

        $event = new CreateRecipientSourceEvent($entity, $recipientSource);
        $eventDispatcher->dispatch(CoreEvents::CREATE_RECIPIENT_SOURCE, $event);

        return $event->getRecipientSource();
    }
}
