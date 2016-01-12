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
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function createRecipientSource(RecipientSource $entity)
    {
        $recipientSource = new RecipientsRecipientSource();

        if ($entity->getFilter()) {
            if ($entity->getFilterByMailingLists()) {
                $recipientSource->setFilteredMailingLists($entity->getMailingLists()->toArray());
            }
            if ($entity->getRecipientsUsePropertyFilter()) {
                $recipientSource->setFilteredProperties($entity->getRecipientsPropertyFilter());
            }
        }

        $this->parseRecipientsManageSubscriptionPage($entity, $recipientSource);
        $this->parseRecipientsUnsubscribePage($entity, $recipientSource);

        return $recipientSource;
    }

    /**
     * @param RecipientSource           $entity
     * @param RecipientsRecipientSource $recipientSource
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function parseRecipientsManageSubscriptionPage(
        RecipientSource $entity,
        RecipientsRecipientSource &$recipientSource
    ) {
        if (!$entity->getRecipientsManageSubscriptionPage()) {
            return;
        }

        global $container;

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $getPageDetailsEvent =
            new GetPageDetailsEvent($entity->getRecipientsManageSubscriptionPage());
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $getPageDetailsEvent);

        $generateFrontendUrlEvent = new GenerateFrontendUrlEvent($getPageDetailsEvent->getPageDetails());
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $generateFrontendUrlEvent);

        $url = $generateFrontendUrlEvent->getUrl();
        $url .= (strpos($url, '?') !== false ? '&' : '?') . 'avisota_subscription_email=##email##';

        if (!preg_match('~^\w+:~', $url)) {

            $url         = rtrim(\Environment::get('base'), '/') . '/' . ltrim($url, '/');
        }

        $recipientSource->setManageSubscriptionUrlPattern($url);

        $event = new CreateRecipientSourceEvent($entity, $recipientSource);
        $eventDispatcher->dispatch(CoreEvents::CREATE_RECIPIENT_SOURCE, $event);

        $recipientSource = $event->getRecipientSource();
    }

    /**
     * @param RecipientSource           $entity
     * @param RecipientsRecipientSource $recipientSource
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function parseRecipientsUnsubscribePage(
        RecipientSource $entity,
        RecipientsRecipientSource &$recipientSource
    ) {
        if (!$entity->getRecipientsUnsubscribePage()) {
            return;
        }

        global $container;

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $getPageDetailsEvent = new GetPageDetailsEvent($entity->getRecipientsUnsubscribePage());
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $getPageDetailsEvent);

        $generateFrontendUrlEvent = new GenerateFrontendUrlEvent($getPageDetailsEvent->getPageDetails());
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $generateFrontendUrlEvent);

        $url = $generateFrontendUrlEvent->getUrl();
        $url .= (strpos($url, '?') !== false ? '&' : '?') . 'avisota_subscription_email=##email##';

        if (!preg_match('~^\w+:~', $url)) {

            $url         = rtrim(\Environment::get('base'), '/') . '/' . ltrim($url, '/');
        }

        $recipientSource->setUnsubscribeUrlPattern($url);

        $event = new CreateRecipientSourceEvent($entity, $recipientSource);
        $eventDispatcher->dispatch(CoreEvents::CREATE_RECIPIENT_SOURCE, $event);

        $recipientSource = $event->getRecipientSource();
    }
}
