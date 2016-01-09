<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2015 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\SubscriptionRecipient\DataContainer;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Subscription;
use Avisota\Contao\Subscription\SubscriptionManager;
use Contao\Doctrine\ORM\DataContainer\General\EntityModel;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\DecodePropertyValueForWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ModelToLabelEvent;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Recipient
 *
 * @package Avisota\Contao\SubscriptionRecipient\DataContainer
 */
class Recipient implements EventSubscriberInterface
{
    static protected $instance;

    /**
     * @return Recipient
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            DcGeneralEvents::ACTION                                                    => 'handleAction',
            ModelToLabelEvent::NAME                                                    => 'createLabel',
            DecodePropertyValueForWidgetEvent::NAME . '[orm_avisota_recipient][email]' => 'decodeEmail',
        );
    }

    /**
     * Recipient constructor.
     */
    public function __construct()
    {
        static::$instance = $this;
    }

    /**
     * Handle custom events.
     *
     * @param ActionEvent $event
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function handleAction(ActionEvent $event)
    {
        global $TL_LANG,
               $container;

        if ($event->getResponse()
            || $event->getEnvironment()->getDataDefinition()->getName() != 'orm_avisota_recipient'
        ) {
            return;
        }

        $environment      = $event->getEnvironment();
        $eventDispatcher  = $environment->getEventDispatcher();
        $action           = $event->getAction();
        $name             = $action->getName();
        $input            = $environment->getInputProvider();
        $subscribeOptions = SubscriptionManager::OPT_IGNORE_BLACKLIST;

        switch ($name) {
            case 'confirm-subscription':
                $subscriptionRepository = EntityHelper::getRepository('Avisota\Contao:Subscription');
                $subscriptionId         = $input->getParameter('subscription');
                $subscription           = $subscriptionRepository->find($subscriptionId);
                /** @var Subscription $subscription */

                /** @var SubscriptionManager $subscriptionManager */
                $subscriptionManager = $container['avisota.subscription'];
                $subscriptionManager->confirm($subscription);

                $event = AddMessageEvent::createConfirm(
                    sprintf(
                        $TL_LANG['orm_avisota_recipient']['confirm-subscription'],
                        $subscription->getRecipient()->getTitle(),
                        $subscription->getMailingList()
                            ? $subscription->getMailingList()->getTitle()
                            : $TL_LANG['orm_avisota_recipient']['subscription_global']
                    )
                );
                $eventDispatcher->dispatch(ContaoEvents::MESSAGE_ADD, $event);

                $event = new RedirectEvent(
                    'contao/main.php?do=avisota_recipients#' . md5($subscription->getRecipient()->getEmail())
                );
                $environment->getEventDispatcher()->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
                break;

            case 'remove-subscription':
                $subscriptionRepository = EntityHelper::getRepository('Avisota\Contao:Subscription');
                $subscriptionId         = $input->getParameter('subscription');
                $subscription           = $subscriptionRepository->find($subscriptionId);
                /** @var Subscription $subscription */

                /** @var SubscriptionManager $subscriptionManager */
                $subscriptionManager = $container['avisota.subscription'];
                $subscriptionManager->unsubscribe($subscription);

                $event = AddMessageEvent::createConfirm(
                    sprintf(
                        $TL_LANG['orm_avisota_recipient']['remove-subscription'],
                        $subscription->getRecipient()->getTitle(),
                        $subscription->getMailingList()
                            ? $subscription->getMailingList()->getTitle()
                            : $TL_LANG['orm_avisota_recipient']['subscription_global']
                    )
                );
                $eventDispatcher->dispatch(ContaoEvents::MESSAGE_ADD, $event);

                $event = new RedirectEvent(
                    'contao/main.php?do=avisota_recipients#' . md5($subscription->getRecipient()->getEmail())
                );
                $environment->getEventDispatcher()->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
                break;

            case 'subscribe-confirmed':
                $subscribeOptions |= SubscriptionManager::OPT_ACTIVATE;

            case 'subscribe':
                $recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');
                $recipientId         = $input->getParameter('recipient');
                $recipient           = $recipientRepository->find($recipientId);
                /** @var \Avisota\Contao\Entity\Recipient $recipient */

                $mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
                $mailingListId         = $input->getParameter('mailing-list');
                $mailingList           = $mailingListRepository->find($mailingListId);
                /** @var MailingList $mailingList */

                /** @var SubscriptionManager $subscriptionManager */
                $subscriptionManager = $container['avisota.subscription'];
                $subscriptionManager->subscribe($recipient, $mailingList, $subscribeOptions);

                $event = AddMessageEvent::createConfirm(
                    sprintf(
                        $TL_LANG['orm_avisota_recipient']['subscribe'],
                        $recipient->getEmail(),
                        $mailingList
                            ? $mailingList->getTitle()
                            : $TL_LANG['orm_avisota_recipient']['subscription_global']
                    )
                );
                $eventDispatcher->dispatch(ContaoEvents::MESSAGE_ADD, $event);

                $event = new RedirectEvent(
                    'contao/main.php?do=avisota_recipients#' . md5($recipient->getEmail())
                );
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
                break;
        }
    }

    /**
     * Create label for recipient.
     *
     * @param ModelToLabelEvent $event
     *
     * @return string
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function createLabel(ModelToLabelEvent $event)
    {
        if ($event->getModel()->getProviderName() != 'orm_avisota_recipient') {
            return;
        }

        global $container,
               $TL_LANG;

        /** @var EntityModel $model */
        $model = $event->getModel();
        /** @var \Avisota\Contao\Entity\Recipient $recipient */
        $recipient = $model->getEntity();

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $database = \Database::getInstance();
        $label    = '';

        // add expand/fold icon
        $generateImageEvent = new GenerateHtmlEvent('folPlus.gif', '', 'class="expand" style="display:none"');
        $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $generateImageEvent);
        $label .= $generateImageEvent->getHtml();

        $generateImageEvent = new GenerateHtmlEvent('folMinus.gif', '', 'class="fold" style="display:none"');
        $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $generateImageEvent);
        $label .= $generateImageEvent->getHtml();

        $email = $recipient->getEmail();
        $name  = trim($recipient->getForename() . ' ' . $recipient->getSurname());

        $label .= sprintf('<a name="%s"></a>', md5($email));

        if (strlen($name)) {
            $label .= sprintf('%s &lt;%s&gt;', $name, $email);
        } else {
            $label .= $email;
        }

        // add recipient email
        $label .= ' <span style="color:#b3b3b3; padding-left:.5em;">(';
        $label .= sprintf(
            $TL_LANG['orm_avisota_recipient']['added_at'],
            $recipient->getCreatedAt()->format(\Config::get('datimFormat'))
        );
        if ($recipient->getAddedById() > 0) {
            $user = $database
                ->prepare("SELECT * FROM tl_user WHERE id=?")
                ->execute($recipient->getAddedById());

            if ($user->next()) {
                $format     = $TL_LANG['orm_avisota_recipient']['added_by'];
                $parameters = array(
                    $user->name,
                    $user->username,
                    'contao/main.php?' . http_build_query(
                        array(
                            'do'  => 'user',
                            'act' => 'edit',
                            'id'  => $user->id,
                            'rt'  => defined('REQUEST_TOKEN') ? REQUEST_TOKEN : null,
                            'ref' => defined('TL_REFERER_ID') ? TL_REFERER_ID : null,
                        )
                    )
                );
            } else {
                $format     = $TL_LANG['orm_avisota_recipient']['added_by_unlinked'];
                $parameters = array(
                    $recipient->getAddedByName(),
                    $recipient->getAddedByUsername()
                );
            }

            $label .= vsprintf($format, $parameters);
        }
        $label .= ')</span>';

        $mailingListRepository  = EntityHelper::getRepository('Avisota\Contao:MailingList');
        $subscriptionRepository = EntityHelper::getRepository('Avisota\Contao:Subscription');

        $queryBuilder = $mailingListRepository->createQueryBuilder('ml');
        $queryBuilder->orderBy('ml.title');
        $query = $queryBuilder->getQuery();

        /** @var MailingList[] $mailingLists */
        $mailingLists = $query->getResult();

        $queryBuilder = $subscriptionRepository->createQueryBuilder('s');
        $expr         = $queryBuilder->expr();
        $queryBuilder
            ->select('s')
            ->where($expr->eq('s.recipientType', ':type'))
            ->andWhere($expr->eq('s.recipientId', ':id'))
            ->setParameter('type', 'Avisota\Contao\Entity\Recipient')
            ->setParameter('id', $recipient->getId());
        $query = $queryBuilder->getQuery();

        /** @var Subscription[] $subscriptions */
        $subscriptions = array();
        /** @var Subscription $subscription */
        foreach ($query->getResult() as $subscription) {
            $mailingListId                 = $subscription->getMailingList()
                ? $subscription->getMailingList()->getId()
                : 'global';
            $subscriptions[$mailingListId] = $subscription;
        }

        $label .= '<table class="tl_listing subscriptions" style="display: none">';

        // global subscription
        $subscription = isset($subscriptions['global'])
            ? $subscriptions['global']
            : null;

        $label .= $this->generateSubscriptionRow($recipient, $eventDispatcher, null, $subscription);

        // mailing list subscription
        foreach ($mailingLists as $mailingList) {
            $subscription = isset($subscriptions[$mailingList->getId()])
                ? $subscriptions[$mailingList->getId()]
                : null;

            $label .= $this->generateSubscriptionRow($recipient, $eventDispatcher, $mailingList, $subscription);
        }

        $label .= '</table>';

        $event->setLabel($label);
    }

    /**
     * @param \Avisota\Contao\Entity\Recipient $recipient
     * @param EventDispatcher                  $eventDispatcher
     * @param MailingList|null                 $mailingList
     * @param Subscription|null                $subscription
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function generateSubscriptionRow(
        \Avisota\Contao\Entity\Recipient $recipient,
        EventDispatcher $eventDispatcher,
        MailingList $mailingList = null,
        Subscription $subscription = null
    ) {
        global $container,
               $TL_LANG;

        /** @var SubscriptionManager $subscriptionManager */
        $subscriptionManager = $container['avisota.subscription'];

        $buffer = '';

        if ($subscription) {
            if ($subscription->getActive()) {
                $icon  = 'visible.gif';
                $class = '';
            } else {
                $icon  = 'invisible.gif';
                $class = 'unconfirmed-subscription';
            }

            $event = new GenerateHtmlEvent($icon);
            $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
            $icon = $event->getHtml();
        } else {
            if ($subscriptionManager->isBlacklisted($recipient, $mailingList)) {
                $event = new GenerateHtmlEvent(
                    'error.gif',
                    $TL_LANG['orm_avisota_recipient']['blacklisted'],
                    sprintf(
                        'title="%s"',
                        specialchars($TL_LANG['orm_avisota_recipient']['blacklisted'])
                    )
                );
                $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);

                $icon  = $event->getHtml();
                $class = 'not-subscribed blacklisted';
            } else {
                $icon  = '';
                $class = 'not-subscribed';
            }
        }

        $buffer .= '<tr><td class="tl_file_list ' . $class . '">';
        $buffer .= $icon;
        $buffer .= '&nbsp;';

        if ($mailingList) {
            $buffer .= $mailingList->getTitle();
        } else {
            $buffer .= $TL_LANG['MSC']['avisota-global-subscription-label'];
        }

        $buffer .= '</td><td class="tl_file_list tl_right_nowrap">';

        if ($subscription) {
            if (!$subscription->getActive()) {
                $title = $TL_LANG['orm_avisota_recipient']['confirm_subscription'];

                $event = new GenerateHtmlEvent('ok.gif', $title, sprintf('title="%s"', specialchars($title)));
                $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
                $icon = $event->getHtml();

                $buffer .= sprintf(
                    '<a href="contao/main.php?do=avisota_recipients'
                    . '&act=confirm-subscription&subscription=%s&ref=%s">%s</a>',
                    $subscription->getId(),
                    defined('TL_REFERER_ID') ? TL_REFERER_ID : '',
                    $icon
                );
            }

            $title = $mailingList
                ? $TL_LANG['orm_avisota_recipient']['unsubscribe']
                : $TL_LANG['orm_avisota_recipient']['unsubscribe_globally'];

            $event = new GenerateHtmlEvent('delete.gif', $title, sprintf('title="%s"', specialchars($title)));
            $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
            $icon = $event->getHtml();

            $buffer .= sprintf(
                ' <a href="contao/main.php?do=avisota_recipients'
                . '&act=remove-subscription&subscription=%s&ref=%s">%s</a>',
                $subscription->getId(),
                defined('TL_REFERER_ID') ? TL_REFERER_ID : '',
                $icon
            );
        } else {
            $title = $mailingList
                ? $TL_LANG['orm_avisota_recipient']['subscribe']
                : $TL_LANG['orm_avisota_recipient']['subscribe_globally'];

            $event = new GenerateHtmlEvent('new.gif', $title, sprintf('title="%s"', specialchars($title)));
            $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
            $icon = $event->getHtml();

            $buffer .= sprintf(
                ' <a href="contao/main.php?do=avisota_recipients'
                . '&act=subscribe&recipient=%s&mailing-list=%s&ref=%s">%s</a>',
                $recipient->getId(),
                $mailingList
                    ? $mailingList->getId()
                    : 'global',
                defined('TL_REFERER_ID') ? TL_REFERER_ID : '',
                $icon
            );

            $title = $mailingList
                ? $TL_LANG['orm_avisota_recipient']['subscribe_confirmed']
                : $TL_LANG['orm_avisota_recipient']['subscribe_globally_confirmed'];

            $event = new GenerateHtmlEvent('copychilds.gif', $title, sprintf('title="%s"', specialchars($title)));
            $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
            $icon = $event->getHtml();

            $buffer .= sprintf(
                ' <a href="contao/main.php?do=avisota_recipients'
                . '&act=subscribe-confirmed&recipient=%s&mailing-list=%s&ref=%s">%s</a>',
                $recipient->getId(),
                $mailingList
                    ? $mailingList->getId()
                    : 'global',
                defined('TL_REFERER_ID') ? TL_REFERER_ID : '',
                $icon
            );
        }

        $buffer .= '</td></tr>';

        return $buffer;
    }

    /**
     * Make email lowercase.
     *
     * @param DecodePropertyValueForWidgetEvent $event
     */
    public function decodeEmail(DecodePropertyValueForWidgetEvent $event)
    {
        $event->setValue(strtolower($event->getValue()));
    }
}
