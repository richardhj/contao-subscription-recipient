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

namespace Avisota\Contao\SubscriptionRecipient\Recipient;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Subscription\Event\CollectSubscriptionListsEvent;
use Contao\Doctrine\ORM\EntityHelper;

/**
 * Class Subscription
 *
 * @package Avisota\Contao\SubscriptionRecipient\Recipient
 */
class Subscription extends \Controller
{
    /**
     * @param CollectSubscriptionListsEvent $event
     * @SuppressWarnings(PHPMD.LongVariable)
     * TODO is this method really in use
     */
    public static function collectSubscriptionLists(CollectSubscriptionListsEvent $event)
    {
        $mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
        /** @var MailingList[] $mailingLists */
        $mailingLists = $mailingListRepository->findAll();

        $mailingListOptions = array();
        foreach ($mailingLists as $mailingList) {
            $mailingListOptions['mailing_list:' . $mailingList->id()] = $mailingList->getTitle();
        }

        $options                 = $event->getOptions();
        $options['mailing_list'] = $mailingListOptions;
    }
}
