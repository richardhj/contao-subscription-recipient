<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\SubscriptionRecipient\DataContainer;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient as RecipientEntity;
use Avisota\Contao\Entity\Subscription;
use Avisota\Contao\Subscription\SubscriptionManager;
use Contao\Doctrine\ORM\DataContainer\General\EntityModel;
use Contao\Doctrine\ORM\EntityHelper;
use Contao\Doctrine\ORM\EntityInterface;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\DecodePropertyValueForWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetBreadcrumbEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ModelToLabelEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Recipient
 *
 * @package Avisota\Contao\SubscriptionRecipient\DataContainer
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
            DcGeneralEvents::ACTION => array(
                array('handleAction'),
            ),

            ModelToLabelEvent::NAME => array(
                array('createLabel'),
            ),

            DecodePropertyValueForWidgetEvent::NAME => array(
                array('decodeEmail'),
            ),

            GetBreadcrumbEvent::NAME => array(
                array('getBreadCrumb')
            )
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
        if ($event->getResponse()
            || $event->getEnvironment()->getDataDefinition()->getName() != 'orm_avisota_recipient'
        ) {
            return;
        }

        $action           = $event->getAction();
        $name             = $action->getName();
        $subscribeOptions = SubscriptionManager::OPT_IGNORE_BLACKLIST;

        switch ($name) {
            case 'confirm-subscription':
                $this->handleConfirmSubscriptionAction($event);
                break;

            case 'remove-subscription':
                $this->handleRemoveSubscriptionAction($event);
                break;

            case 'subscribe-confirmed':
                $subscribeOptions |= SubscriptionManager::OPT_ACTIVATE;
                $this->handleSubscribeAction($event, $subscribeOptions);
                break;

            case 'subscribe':
                $this->handleSubscribeAction($event, $subscribeOptions);
                break;
        }
    }

    /**
     * @param ActionEvent $event
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function handleConfirmSubscriptionAction(ActionEvent $event)
    {
        global $container,
               $TL_LANG;

        $environment     = $event->getEnvironment();
        $eventDispatcher = $environment->getEventDispatcher();

        $subscriptionRepository = EntityHelper::getRepository('Avisota\Contao:Subscription');
        $subscriptionId         = $environment->getInputProvider()->getParameter('subscription');
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
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
    }

    /**
     * @param ActionEvent $event
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function handleRemoveSubscriptionAction(ActionEvent $event)
    {
        global $container,
               $TL_LANG;

        $environment     = $event->getEnvironment();
        $eventDispatcher = $environment->getEventDispatcher();

        $subscriptionRepository = EntityHelper::getRepository('Avisota\Contao:Subscription');
        $subscriptionId         = $environment->getInputProvider()->getParameter('subscription');
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
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
    }

    /**
     * @param ActionEvent $event
     * @param             $subscribeOptions
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function handleSubscribeAction(ActionEvent $event, $subscribeOptions)
    {
        global $container,
               $TL_LANG;

        $environment     = $event->getEnvironment();
        $eventDispatcher = $environment->getEventDispatcher();
        $inputProvider   = $environment->getInputProvider();

        $recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');
        $recipientId         = $inputProvider->getParameter('recipient');
        $recipient           = $recipientRepository->find($recipientId);
        /** @var \Avisota\Contao\Entity\Recipient $recipient */

        $mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
        $mailingListId         = $inputProvider->getParameter('mailing-list');
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

        global $container;

        /** @var EntityModel $model */
        $model = $event->getModel();
        /** @var \Avisota\Contao\Entity\Recipient $recipient */
        $recipient = $model->getEntity();

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $label = $this->addExpandFoldIconToLabel($eventDispatcher);
        $label .= $this->addRecipientToLabel($recipient);
        $label .= $this->addAddedByToLabel($recipient);
        $label .= $this->addSubscriptionToLabel($recipient, $eventDispatcher);

        $event->setLabel($label);
    }

    /**
     * @param EntityInterface $recipient
     *
     * @return string
     */
    protected function addRecipientToLabel(EntityInterface $recipient)
    {
        $email = $recipient->getEmail();
        $name  = trim($recipient->getForename() . ' ' . $recipient->getSurname());

        $label = sprintf('<a name="%s"></a>', md5($email));

        if (strlen($name)) {
            $label .= sprintf('%s &lt;%s&gt;', $name, $email);
        } else {
            $label .= $email;
        }

        return $label;
    }

    /**
     * @param EntityInterface $recipient
     * @param EventDispatcher $eventDispatcher
     *
     * @return string
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function addSubscriptionToLabel(EntityInterface $recipient, EventDispatcher $eventDispatcher)
    {
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

        $label = '<table class="tl_listing subscriptions" style="display: none">';

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

        return $label;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     *
     * @return string
     */
    protected function addExpandFoldIconToLabel(EventDispatcher $eventDispatcher)
    {
        $label = '';

        foreach (array('expand' => 'folPlus.gif', 'fold' => 'folMinus.gif') as $state => $icon) {
            $generateImageEvent = new GenerateHtmlEvent($icon, '', 'class="' . $state . '" style="display:none"');
            $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $generateImageEvent);
            $label .= $generateImageEvent->getHtml();
        }

        return $label;
    }

    /**
     * @param EntityInterface $recipient
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function addAddedByToLabel(EntityInterface $recipient)
    {
        global $TL_LANG;

        $label = ' <span style="color:#b3b3b3; padding-left:.5em;">(';
        $label .= sprintf(
            $TL_LANG['orm_avisota_recipient']['added_at'],
            $recipient->getCreatedAt()->format(\Config::get('datimFormat'))
        );

        if ($recipient->getAddedById() > 0) {
            $database = \Database::getInstance();
            $user     = $database
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

        return $label;
    }

    /**
     * @param RecipientEntity   $recipient
     * @param EventDispatcher   $eventDispatcher
     * @param MailingList|null  $mailingList
     * @param Subscription|null $subscription
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function generateSubscriptionRow(
        RecipientEntity $recipient,
        EventDispatcher $eventDispatcher,
        MailingList $mailingList = null,
        Subscription $subscription = null
    ) {
        global $TL_LANG;

        $buffer = '';

        list($icon, $class) = $this->getStateIconAndClass($recipient, $eventDispatcher, $mailingList, $subscription);

        $buffer .= '<tr><td class="tl_file_list ' . $class . '">';
        $buffer .= $icon;
        $buffer .= '&nbsp;';

        if ($mailingList) {
            $buffer .= $mailingList->getTitle();
        } else {
            $buffer .= $TL_LANG['MSC']['avisota-global-subscription-label'];
        }

        $buffer .= '</td><td class="tl_file_list tl_right_nowrap">';
        $buffer .= $this->getSubscribeActionLinks($recipient, $eventDispatcher, $mailingList, $subscription);
        $buffer .= '</td></tr>';

        return $buffer;
    }

    /**
     * @param RecipientEntity   $recipient
     * @param EventDispatcher   $eventDispatcher
     * @param MailingList|null  $mailingList
     * @param Subscription|null $subscription
     *
     * @return array
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function getStateIconAndClass(
        RecipientEntity $recipient,
        EventDispatcher $eventDispatcher,
        MailingList $mailingList = null,
        Subscription $subscription = null
    ) {
        global $TL_LANG,
               $container;

        /** @var SubscriptionManager $subscriptionManager */
        $subscriptionManager = $container['avisota.subscription'];

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

        return array($icon, $class);
    }

    /**
     * @param RecipientEntity   $recipient
     * @param EventDispatcher   $eventDispatcher
     * @param MailingList|null  $mailingList
     * @param Subscription|null $subscription
     *
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getSubscribeActionLinks(
        RecipientEntity $recipient,
        EventDispatcher $eventDispatcher,
        MailingList $mailingList = null,
        Subscription $subscription = null
    ) {
        $buffer = '';
        if ($subscription) {
            $buffer .= $this->generateSubscriptionActivationLink($subscription);
            $buffer .= $this->generateSubscriptionRemoveLink($subscription, $mailingList);
        }

        $buffer .= $this->generateSubscriptionSubscribeLink($recipient, $subscription, $mailingList);
        $buffer .= $this->generateSubscriptionSubscribeConfirmLink($recipient, $subscription, $mailingList);

        return $buffer;
    }

    /**
     * @param Subscription $subscription
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function generateSubscriptionActivationLink(Subscription $subscription)
    {
        if ($subscription->getActive()) {
            return '';
        }

        global $TL_LANG,
               $container;

        $eventDispatcher = $container['event-dispatcher'];

        $title = $TL_LANG['orm_avisota_recipient']['confirm_subscription'];

        $event = new GenerateHtmlEvent('ok.gif', $title, sprintf('title="%s"', specialchars($title)));
        $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
        $icon = $event->getHtml();

        return sprintf(
            '<a href="contao/main.php?do=avisota_recipients'
            . '&act=confirm-subscription&subscription=%s&ref=%s">%s</a>',
            $subscription->getId(),
            defined('TL_REFERER_ID') ? TL_REFERER_ID : '',
            $icon
        );
    }

    /**
     * @param Subscription $subscription
     * @param MailingList  $mailingList
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function generateSubscriptionRemoveLink(Subscription $subscription, MailingList $mailingList = null)
    {
        global $TL_LANG,
               $container;

        $eventDispatcher = $container['event-dispatcher'];

        $title = $mailingList
            ? $TL_LANG['orm_avisota_recipient']['unsubscribe']
            : $TL_LANG['orm_avisota_recipient']['unsubscribe_globally'];

        $event = new GenerateHtmlEvent('delete.gif', $title, sprintf('title="%s"', specialchars($title)));
        $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
        $icon = $event->getHtml();

        return sprintf(
            ' <a href="contao/main.php?do=avisota_recipients'
            . '&act=remove-subscription&subscription=%s&ref=%s">%s</a>',
            $subscription->getId(),
            defined('TL_REFERER_ID') ? TL_REFERER_ID : '',
            $icon
        );
    }

    /**
     * @param RecipientEntity   $recipient
     * @param Subscription|null $subscription
     * @param MailingList|null  $mailingList
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function generateSubscriptionSubscribeLink(
        RecipientEntity $recipient,
        Subscription $subscription = null,
        MailingList $mailingList = null
    ) {
        if ($subscription) {
            return '';
        }

        global $TL_LANG,
               $container;

        $eventDispatcher = $container['event-dispatcher'];

        $title = $mailingList
            ? $TL_LANG['orm_avisota_recipient']['subscribe']
            : $TL_LANG['orm_avisota_recipient']['subscribe_globally'];

        $event = new GenerateHtmlEvent('new.gif', $title, sprintf('title="%s"', specialchars($title)));
        $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
        $icon = $event->getHtml();

        return sprintf(
            ' <a href="contao/main.php?do=avisota_recipients'
            . '&act=subscribe&recipient=%s&mailing-list=%s&ref=%s">%s</a>',
            $recipient->getId(),
            $mailingList
                ? $mailingList->getId()
                : 'global',
            defined('TL_REFERER_ID') ? TL_REFERER_ID : '',
            $icon
        );
    }

    /**
     * @param RecipientEntity   $recipient
     * @param Subscription|null $subscription
     * @param MailingList|null  $mailingList
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function generateSubscriptionSubscribeConfirmLink(
        RecipientEntity $recipient,
        Subscription $subscription = null,
        MailingList $mailingList = null
    ) {
        if ($subscription) {
            return '';
        }

        global $TL_LANG,
               $container;

        $eventDispatcher = $container['event-dispatcher'];
        $title           = $mailingList
            ? $TL_LANG['orm_avisota_recipient']['subscribe_confirmed']
            : $TL_LANG['orm_avisota_recipient']['subscribe_globally_confirmed'];

        $event = new GenerateHtmlEvent('copychilds.gif', $title, sprintf('title="%s"', specialchars($title)));
        $eventDispatcher->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);
        $icon = $event->getHtml();

        return sprintf(
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

    /**
     * Make email lowercase.
     *
     * @param DecodePropertyValueForWidgetEvent $event
     */
    public function decodeEmail(DecodePropertyValueForWidgetEvent $event)
    {
        if ($event->getModel()->getProviderName() != 'orm_avisota_recipient'
            || $event->getProperty() != 'email'
        ) {
            return;
        }

        $event->setValue(strtolower($event->getValue()));
    }

    /**
     * Get the bread crumb elements.
     *
     * @param GetBreadcrumbEvent $event This event.
     *
     * @return void
     */
    public function getBreadCrumb(GetBreadcrumbEvent $event)
    {
        $environment   = $event->getEnvironment();
        $dataDefinition = $environment->getDataDefinition();
        $inputProvider = $environment->getInputProvider();
        $translator = $environment->getTranslator();

        $modelParameter = $inputProvider->hasParameter('act') ? 'id' : 'pid';

        if ($dataDefinition->getName() !== 'orm_avisota_recipient'
            || !$inputProvider->hasParameter($modelParameter)
        ) {
            return;
        }

        $modelId = ModelId::fromSerialized($inputProvider->getParameter($modelParameter));
        if ($modelId->getDataProviderName() !== 'orm_avisota_recipient') {
            return;
        }

        $elements = $event->getElements();

        $urlBuilder = new UrlBuilder();
        $urlBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $elements[] = array(
            'icon' => 'assets/avisota/subscription-recipient/images/recipients.png',
            'text' => $translator->translate('avisota_recipients.0', 'MOD'),
            'url'  => $urlBuilder->getUrl()
        );

        $event->setElements($elements);
    }
}
