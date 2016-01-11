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

namespace Avisota\Contao\SubscriptionRecipient;

use Avisota\Contao\Core\DataContainer\OptionsBuilder;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\Subscription;
use Avisota\Contao\Subscription\Event\PrepareSubscriptionEvent;
use Avisota\Contao\Subscription\Event\ResolveRecipientEvent;
use Avisota\Contao\Subscription\Event\SubscriptionAwareEvent;
use Avisota\Contao\Subscription\SubscriptionEvents;
use Avisota\Contao\Subscription\SubscriptionManager;
use Avisota\Contao\SubscriptionNotificationCenterBridge\Event\BuildTokensFromRecipientEvent;
use Avisota\Contao\SubscriptionRecipient\Event\ExportRecipientPropertyEvent;
use Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent;
use Contao\Doctrine\DBAL\DoctrineDbalEvents;
use Contao\Doctrine\DBAL\Event\InitializeEventManager;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReloadEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetEditModeButtonsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use ContaoCommunityAlliance\DcGeneral\Event\EventPropagator;
use ContaoCommunityAlliance\DcGeneral\Factory\DcGeneralFactory;
use MenAtWork\MultiColumnWizard\Event\GetOptionsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EventsSubscriber
 */
class EventsSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            DcGeneralEvents::ACTION => array(
                array('injectAutocompleter'),
                array('migrateRecipients'),
            ),

            GetEditModeButtonsEvent::NAME => array(
                array('getExportButtons'),
                array('getMigrateButtons'),
            ),

            SubscriptionEvents::UNSUBSCRIBE => array(
                array('cleanRecipient'),
            ),

            SubscriptionEvents::CLEAN_SUBSCRIPTION => array(
                array('cleanRecipient'),
            ),

            SubscriptionEvents::PREPARE_SUBSCRIPTION => array(
                array('prepareSubscription'),
            ),

            SubscriptionEvents::RESOLVE_RECIPIENT => array(
                array('resolveRecipient'),
            ),

            SubscriptionEvents::CREATE_RECIPIENT_PROPERTIES_OPTIONS => array(
                array('createRecipientPropertiesOptions'),
            ),

            // TODO: Where come this event ??
            GetPropertyOptionsEvent::NAME . '[orm_avisota_recipient_source][recipientsPropertyFilter][recipientsPropertyFilter_property]' => 'bypassCreateRecipientPropertiesOptions',

            GetOptionsEvent::NAME => array(
                array('bypassCreateMailingListOptions'),
            ),

            DoctrineDbalEvents::INITIALIZE_EVENT_MANAGER => array(
                array('initializeEventManager'),
            ),

            RecipientEvents::MIGRATE_RECIPIENT => array(
                array('collectMemberPersonals'),
            ),

            RecipientEvents::EXPORT_RECIPIENT_PROPERTY => array(
                array('exportRecipientProperties'),
            ),

            RecipientDataContainerEvents::CREATE_IMPORTABLE_RECIPIENT_FIELD_OPTIONS => array(
                array('createImportableRecipientFieldOptions'),
            ),

            RecipientDataContainerEvents::CREATE_EDITABLE_RECIPIENT_FIELD_OPTIONS => array(
                array('createEditableRecipientFieldOptions'),
            ),

            RecipientDataContainerEvents::CREATE_SUBSCRIBE_TEMPLATE_OPTIONS => array(
                array('createSubscribeTemplateOptions'),
            ),

            RecipientDataContainerEvents::CREATE_UNSUBSCRIBE_TEMPLATE_OPTIONS => array(
                array('createUnsubscribeTemplateOptions'),
            ),

            RecipientDataContainerEvents::CREATE_SUBSCRIPTION_TEMPLATE_OPTIONS => array(
                array('createSubscriptionTemplateOptions'),
            ),

            'avisota.subscription-notification-center-bridge.build-tokens-from-recipient' => 'buildRecipientTokens',
        );
    }

    /**
     * @param ActionEvent                   $event
     * @param null                          $eventName
     * @param EventDispatcherInterface|null $eventDispatcher
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function injectAutocompleter(
        ActionEvent $event,
        $eventName = null,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        global $container,
               $TL_CSS,
               $TL_JAVASCRIPT,
               $TL_MOOTOOLS;

        static $injected;

        if (!$injected
            && $event->getEnvironment()->getDataDefinition()->getName() == 'orm_avisota_salutation'
        ) {
            // backwards compatibility
            if (!$eventDispatcher) {
                /** @var EventDispatcher $eventDispatcher */
                $eventDispatcher = $container['event-dispatcher'];
            }

            // load language file
            $loadEvent = new LoadLanguageFileEvent('orm_avisota_recipient');
            $eventDispatcher->dispatch(ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE, $loadEvent);

            // load data container
            $loadEvent = new LoadDataContainerEvent('orm_avisota_recipient');
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER, $loadEvent);

            // inject styles
            $TL_CSS[] = 'assets/avisota/subscription-recipient/css/meio.autocomplete.css';

            // inject scripts
            $TL_JAVASCRIPT[] = 'assets/avisota/subscription-recipient/js/Meio.Autocomplete.js';
            $TL_JAVASCRIPT[] = 'assets/avisota/subscription-recipient/js/mootools-more-1.5.0.js';

            // build container for orm_avisota_recipient
            $factory = DcGeneralFactory::deriveFromEnvironment($event->getEnvironment());
            $factory->setContainerName('orm_avisota_recipient');
            $containerFactory = $factory->createContainer();

            // build token list
            $tokens = array();
            foreach ($containerFactory->getPropertiesDefinition()->getPropertyNames() as $propertyName) {
                $tokens[] = array(
                    'value' => $propertyName,
                    'text'  => sprintf('##%s##', $propertyName),
                );
            }
            $tokens = json_encode($tokens);

            // inject runtime code
            $TL_MOOTOOLS[] = <<<EOF
<script>
var element = $('ctrl_salutation');
if (element) {
	var tokens = {$tokens};
	var options = {
		filter: {
			type: 'contains',
			path: 'text'
		},
		tokenize: {
			get: function(element) {
				var text     = element.get('value');
				var position = element.getCaretPosition();
				var start    = text.lastIndexOf(' ', position - 1);
				var end      = text.indexOf(' ', position);

				if (start == -1) {
					start = 0;
				}
				else {
					start ++;
				}
				if (end == -1) {
					end = text.length;
				}

				var token = text.substring(start, end);
				console.log('position: ' + position + ', start: ' + start + ', end: ' + end + ', token: ' + token);

				return token;
			},
			set: function(element, token) {
				var text     = element.get('value');
				var position = element.getCaretPosition();
				var start    = text.lastIndexOf(' ', position - 1);
				var end      = text.indexOf(' ', position);

				if (start == -1) {
					start = 0;
				}
				else {
					start ++;
				}
				if (end == -1) {
					end = text.length;
				}

				text = text.substring(0, start) + token + text.substring(end);

				element.set('value', text);
				element.setCaretPosition(start + token.length);
			}
		}
	};
	new Meio.Autocomplete(element, tokens, options);
}
</script>
EOF;

            $injected = true;
        }
    }

    /**
     * @param ActionEvent                   $event
     * @param null                          $eventName
     * @param EventDispatcherInterface|null $eventDispatcher
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function migrateRecipients(
        ActionEvent $event,
        $eventName = null,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $environment = $event->getEnvironment();
        if ($environment->getDataDefinition()->getName() == 'mem_avisota_recipient_migrate'
            && $event->getAction()
                   ->getName() == 'migrate'
        ) {
            global $container,
                   $TL_LANG;

            if (!$eventDispatcher) {
                $eventDispatcher = $event->getDispatcher();
            }

            $input       = $environment->getInputProvider();
            $migrationId = 'AVISOTA_MIGRATE_RECIPIENT_' . $input->getParameter('migration');

            if (empty(\Session::getInstance()->get($migrationId))) {
                $addToUrlEvent = new AddToUrlEvent('act=&migration=');
                $eventDispatcher->dispatch(ContaoEvents::BACKEND_ADD_TO_URL, $addToUrlEvent);

                $redirectEvent = new RedirectEvent($addToUrlEvent->getUrl());
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $redirectEvent);
                return;
            }

            $migrationSettings = \Session::getInstance()->get($migrationId);


            /** @var \Doctrine\DBAL\Connection $connection */
            $connection = $container['doctrine.connection.default'];
            $translator = $environment->getTranslator();

            $entityManager         = EntityHelper::getEntityManager();
            $recipientRepository   = EntityHelper::getRepository('Avisota\Contao:Recipient');
            $mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');

            $channels                  = array();
            $channelMailingListMapping = array();
            foreach ($migrationSettings['channels'] as $channel) {
                $mailingList                         = $channel['mailingList'];
                $channel                             = $channel['channel'];
                $channels[]                          = $connection->quote($channel);
                $channelMailingListMapping[$channel] = $mailingListRepository->find($mailingList);
            }

            $offset   = isset($migrationSettings['offset']) ? $migrationSettings['offset'] : 0;
            $skipped  = isset($migrationSettings['skipped']) ? $migrationSettings['skipped'] : 0;
            $migrated = isset($migrationSettings['migrated']) ? $migrationSettings['migrated'] : 0;

            $queryBuilder = $connection->createQueryBuilder();
            /** @var \PDOStatement $stmt */
            $stmt = $queryBuilder
                ->select('*')
                ->from('tl_newsletter_recipients', 'r')
                ->where(
                    $queryBuilder
                        ->expr()
                        ->in('pid', $channels)
                )
                ->orderBy('r.pid')
                ->addOrderBy('r.email')
                ->setFirstResult($offset)
                ->setMaxResults(10)
                ->execute();

            /** @var SubscriptionManager $subscriptionManager */
            $subscriptionManager = $container['avisota.subscription'];
            $subscribeOptions    = 0;

            if ($migrationSettings['ignoreBlacklist']) {
                $subscribeOptions |= SubscriptionManager::OPT_IGNORE_BLACKLIST;
            }

            $user     = \BackendUser::getInstance();
            $response = new \StringBuilder();
            $response->append('<div class="tl_buttons">&nbsp;</div>');
            $response->append('<h2 class="sub_headline">');
            $response->append($translator->translate('running', 'mem_avisota_recipient_migrate'));
            $response->append('</h2>');
            $response->append('<div class="tl_formbody_edit"><ul>');

            $contaoRecipients = $stmt->fetchAll();
            foreach ($contaoRecipients as $contaoRecipientData) {
                $recipient = $recipientRepository->findOneBy(array('email' => $contaoRecipientData['email']));

                if (!$recipient) {
                    $response->append('<li>');
                    $response->append(
                        sprintf(
                            $translator->translate('created', 'mem_avisota_recipient_migrate'),
                            $contaoRecipientData['email']
                        )
                    );
                    $response->append('</li>');

                    $recipient = new Recipient();
                    $recipient->setEmail($contaoRecipientData['email']);
                    $recipient->setAddedById($user->id);
                    $recipient->setAddedByName($user->name);
                    $recipient->setAddedByUsername($user->username);
                } else {
                    if (!$migrationSettings['overwrite']) {
                        $response->append('<li>');
                        $response->append(
                            sprintf(
                                $translator->translate('skipped', 'mem_avisota_recipient_migrate'),
                                $contaoRecipientData['email']
                            )
                        );
                        $response->append('</li>');

                        $skipped++;
                        continue;
                    } else {
                        $response->append('<li>');
                        $response->append(
                            sprintf(
                                $translator->translate('overwriten', 'mem_avisota_recipient_migrate'),
                                $contaoRecipientData['email']
                            )
                        );
                        $response->append('</li>');
                    }
                }

                $mailingList = $channelMailingListMapping[$contaoRecipientData['pid']];

                if (!$mailingList) {
                    // graceful ignore missing mailing lists
                    $skipped++;
                    continue;
                }

                $migrateRecipientEvent =
                    new MigrateRecipientEvent($migrationSettings, $contaoRecipientData, $recipient);
                $eventDispatcher->dispatch(RecipientEvents::MIGRATE_RECIPIENT, $migrateRecipientEvent);

                $entityManager->persist($recipient);

                $subscriptionManager->subscribe(
                    $recipient,
                    $mailingList,
                    ($contaoRecipientData['active'] ? SubscriptionManager::OPT_ACTIVATE : 0) | $subscribeOptions
                );

                $migrated++;
            }
            $entityManager->flush();

            if (count($contaoRecipients) < 10) {
                \Session::getInstance()->remove($migrationId);

                if (!is_array(\Session::getInstance()->get('TL_CONFIRM'))) {
                    \Session::getInstance()->set('TL_CONFIRM', (array) \Session::getInstance()->get('TL_CONFIRM'));
                }

                $confirmSession   = \Session::getInstance()->get('TL_CONFIRM');
                $confirmSession[] = sprintf(
                    $TL_LANG['mem_avisota_recipient_migrate']['migrated'],
                    $migrated,
                    $skipped
                );
                \Session::getInstance()->set('TL_CONFIRM', $confirmSession);

                $addToUrlEvent = new AddToUrlEvent('act=&migration=');
                $eventDispatcher->dispatch(ContaoEvents::BACKEND_ADD_TO_URL, $addToUrlEvent);

                $redirectEvent = new RedirectEvent($addToUrlEvent->getUrl());
                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $redirectEvent);
            } else {
                // update session data
                $migrationSettings['offset']   = $offset + count($contaoRecipients);
                $migrationSettings['skipped']  = $skipped;
                $migrationSettings['migrated'] = $migrated;
                \Session::getInstance()->set($migrationId, $migrationSettings);

                $response->append('</ul><br>');
                $response->append(
                    '<script>'
                    . 'window.onload = function() { '
                    . 'document.getElementById("btn_avisota_migrate_reload").disabled = true; '
                    . 'location.reload(); };'
                    . '</script>'
                );

                $response->append(
                    '<p>'
                    . '<button click="location.reload()" class="tl_submit" id="btn_avisota_migrate_reload">'
                );
                $response->append($translator->translate('reload', 'mem_avisota_recipient_migrate'));
                $response->append('</button></p>');

                $response->append('</div>');

                $event->setResponse($response->__toString());
                $event->stopPropagation();
            }
        }
    }

    /**
     * @param SubscriptionAwareEvent $event
     */
    public function cleanRecipient(SubscriptionAwareEvent $event)
    {
        if (!\Config::get('avisota_subscription_recipient_cleanup')) {
            return;
        }

        $subscription = $event->getSubscription();
        $recipient    = $subscription->getRecipient();

        if ($recipient) {
            $subscriptions = $recipient->getSubscriptions();

            if ($subscriptions->isEmpty()
                || $subscriptions->count() == 1
                   && $subscriptions->contains($subscription)
            ) {
                $entityManager = EntityHelper::getEntityManager();
                $entityManager->remove($recipient);
            }
        }
    }

    /**
     * @param PrepareSubscriptionEvent $event
     */
    public function prepareSubscription(PrepareSubscriptionEvent $event)
    {
        if ($event->getRecipient() instanceof Recipient) {
            $event->getSubscription()->setRecipient($event->getRecipient());
        }
    }

    /**
     * @param ResolveRecipientEvent $event
     */
    public function resolveRecipient(ResolveRecipientEvent $event)
    {
        // some other event listener has already resolved the recipient
        if ($event->getRecipient()) {
            return;
        }

        $subscription = $event->getSubscription();

        if ($subscription->getRecipientType() == 'Avisota\Contao\Entity\Recipient') {
            $entityManager = EntityHelper::getEntityManager();
            $repository    = $entityManager->getRepository('Avisota\Contao\Entity\Recipient');
            $recipient     = $repository->find($subscription->getRecipientId());
            $event->setRecipient($recipient);
        }
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createRecipientPropertiesOptions(CreateOptionsEvent $event)
    {
        $options = $event->getOptions();
        $this->getRecipientPropertiesOptions($event->getDataContainer()->getEnvironment(), $options);
    }

    /**
     * @param GetEditModeButtonsEvent $event
     */
    public function getExportButtons(GetEditModeButtonsEvent $event)
    {
        if ($event->getEnvironment()->getDataDefinition()->getName() != 'mem_avisota_recipient_export') {
            return;
        }

        $translator = $event->getEnvironment()->getTranslator();

        $buttons = array(
            'export' => sprintf(
                '<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="%s" />',
                $translator->translate('submit', 'mem_avisota_recipient_export')
            )
        );

        $event->setButtons($buttons);
    }

    /**
     * @param GetPropertyOptionsEvent $event
     */
    public function bypassCreateRecipientPropertiesOptions(GetPropertyOptionsEvent $event)
    {
        $options = $event->getOptions();
        $options = $this->getRecipientPropertiesOptions($event->getEnvironment(), $options);
        $event->setOptions($options);
    }

    /**
     * @param GetOptionsEvent $event
     */
    public function bypassCreateMailingListOptions(GetOptionsEvent $event)
    {
        if (!in_array($event->getSubPropertyName(), array('mailingList', 'channel'))) {
            return;
        }

        global $container;

        /** @var OptionsBuilder $optionsBuilder */
        $optionsBuilder = $container['avisota.core.options-builder'];

        $options = $event->getOptions();
        $options = $optionsBuilder->getMailingListOptions($options);
        $event->setOptions($options);
    }

    /**
     * @param EnvironmentInterface $environment
     * @param array                $options
     *
     * @return array
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function getRecipientPropertiesOptions(EnvironmentInterface $environment, $options = array())
    {
        global $container;

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $loadDataContainerEvent = new LoadDataContainerEvent('orm_avisota_recipient');
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER, $loadDataContainerEvent);

        $loadLanguageFileEvent = new LoadLanguageFileEvent('orm_avisota_recipient');
        $eventDispatcher->dispatch(ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE, $loadLanguageFileEvent);

        $dcGeneralFactory = DcGeneralFactory::deriveFromEnvironment($environment);

        $dcGeneralFactory->setContainerName('orm_avisota_recipient');
        $container  = $dcGeneralFactory->createContainer();
        $properties = $container->getPropertiesDefinition()->getProperties();

        foreach ($properties as $property) {
            $options[$property->getName()] = $property->getLabel() ?: $property->getName();
        }

        return $options;
    }

    /**
     * @param GetEditModeButtonsEvent $event
     */
    public function getMigrateButtons(GetEditModeButtonsEvent $event)
    {
        if ($event->getEnvironment()->getDataDefinition()->getName() != 'mem_avisota_recipient_migrate') {
            return;
        }

        $translator = $event->getEnvironment()->getTranslator();

        $buttons = array(
            'migrate' => sprintf(
                '<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="%s" />',
                $translator->translate('submit', 'mem_avisota_recipient_migrate')
            )
        );

        $event->setButtons($buttons);
    }

    /**
     * @param InitializeEventManager $event
     */
    public function initializeEventManager(InitializeEventManager $event)
    {
        global $container;

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];
        $bridge          = new DoctrineBridgeSubscriber($eventDispatcher);

        $eventManager = $event->getEventManager();
        $eventManager->addEventSubscriber($bridge);
    }

    /**
     * While migrate Contao newsletter recipients, fill with member details.
     *
     * @param MigrateRecipientEvent $event
     */
    public function collectMemberPersonals(MigrateRecipientEvent $event)
    {
        global $container;

        $migrationSettings = $event->getMigrationSettings();

        if ($migrationSettings['importFromMembers']) {
            $recipient = $event->getRecipient();

            /** @var \Doctrine\DBAL\Connection $connection */
            $connection = $container['doctrine.connection.default'];

            $queryBuilder = $connection->createQueryBuilder();

            /** @var \PDOStatement $stmt */
            $stmt   = $queryBuilder
                ->select('*')
                ->from('tl_member', 'm')
                ->where(
                    $queryBuilder
                        ->expr()
                        ->eq('m.email', ':email')
                )
                ->setParameter('email', $recipient->getEmail())
                ->execute();
            $member = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($member) {
                /** @var EntityAccessor $entityAccessor */
                $entityAccessor = $container['doctrine.orm.entityAccessor'];

                foreach ($member as $key => $value) {
                    // graceful conversions
                    switch ($key) {
                        case 'firstname':
                            $key = 'forename';
                            break;
                        case 'lastname':
                            $key = 'surname';
                            break;
                    }

                    if ($entityAccessor->hasProperty($recipient, $key)) {
                        $entityAccessor->setProperty($recipient, $key, $value);
                    }
                }
            }
        }
    }

    /**
     * @param ExportRecipientPropertyEvent $event
     */
    public function exportRecipientProperties(ExportRecipientPropertyEvent $event)
    {
        switch ($event->getPropertyName()) {
            case 'mailingListIds':
            case 'mailingListNames':
                if ($event->getString() !== null) {
                    return;
                }

                $subscriptions = $event->getRecipient()->getSubscriptions();
                $values        = array();

                /** @var Subscription $subscription */
                foreach ($subscriptions as $subscription) {
                    if (!$subscription->getActive()) {
                        continue;
                    }

                    $mailingList = $subscription->getMailingList();

                    if ($mailingList) {
                        switch ($event->getPropertyName()) {
                            case 'mailingListIds':
                                $values[] = $mailingList->getId();
                                break;
                            case 'mailingListNames':
                                $values[] = $mailingList->getTitle();
                                break;
                        }
                    } else {
                        $values[] = 'global';
                    }
                }

                $event->setString(implode("\n", $values));
                break;
        }
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createImportableRecipientFieldOptions(CreateOptionsEvent $event)
    {
        $this->getImportableRecipientFieldOptions($event->getOptions());
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getImportableRecipientFieldOptions($options = array())
    {
        global $container;

        // todo issue to dc-general
        \System::loadLanguageFile('orm_avisota_recipient');

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];
        $dcGeneralFactory = new DcGeneralFactory();
        $dcGeneralFactory->setContainerName('orm_avisota_recipient');
        $dcGeneralFactory->setEventDispatcher($eventDispatcher);
        $containerFactory = $dcGeneralFactory->createContainer();

        foreach ($containerFactory->getPropertiesDefinition()->getProperties() as $property) {
            $extra = $property->getExtra();
            if (isset($extra['importable']) && $extra['importable']) {
                $options[$property->getName()] = $property->getLabel();
            }
        }

        return $options;
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createEditableRecipientFieldOptions(CreateOptionsEvent $event)
    {
        $this->getEditableRecipientFieldOptions($event->getOptions());
    }

    /**
     * @param array $options
     *
     * @return array
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getEditableRecipientFieldOptions($options = array())
    {
        global $container,
               $TL_DCA;

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('orm_avisota_recipient')
        );
        $eventDispatcher->dispatch(
            ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
            new LoadDataContainerEvent('orm_avisota_recipient')
        );

        foreach ($TL_DCA['orm_avisota_recipient']['fields'] as $fieldName => $fieldConfig) {
            if ($fieldConfig['eval']['feEditable']) {
                $options[$fieldName] = $fieldConfig['label'][0];
            }
        }

        return $options;
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createSubscribeTemplateOptions(CreateOptionsEvent $event)
    {
        $options   = $event->getOptions();
        $templates = \TwigHelper::getTemplateGroup('avisota_subscribe_');

        foreach ($templates as $key => $value) {
            $options[$key] = $value;
        }
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createUnsubscribeTemplateOptions(CreateOptionsEvent $event)
    {
        $options   = $event->getOptions();
        $templates = \TwigHelper::getTemplateGroup('avisota_unsubscribe_');

        foreach ($templates as $key => $value) {
            $options[$key] = $value;
        }
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createSubscriptionTemplateOptions(CreateOptionsEvent $event)
    {
        $options   = $event->getOptions();
        $templates = \TwigHelper::getTemplateGroup('avisota_subscription_');

        foreach ($templates as $key => $value) {
            $options[$key] = $value;
        }
    }

    /**
     * @param BuildTokensFromRecipientEvent $event
     */
    public function buildRecipientTokens(BuildTokensFromRecipientEvent $event)
    {
        global $container;

        $recipient = $event->getRecipient();

        if (!$recipient instanceof Recipient) {
            return;
        }

        /** @var EntityAccessor $entityAccessor */
        $entityAccessor = $container['doctrine.orm.entityAccessor'];

        $tokens = $event->getTokens();

        $properties = $entityAccessor->getProperties($recipient);

        foreach ($properties as $key => $value) {
            if (!is_object($value)) {
                $tokens['recipient_' . $key] = $value;
            }
        }
    }
}
