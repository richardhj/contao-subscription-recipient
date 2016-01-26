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

namespace Avisota\Contao\SubscriptionRecipient\Controller;

use Avisota\Contao\Core\DataContainer\OptionsBuilder;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Subscription\SubscriptionManager;
use Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent;
use Avisota\Contao\SubscriptionRecipient\RecipientEvents;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use MenAtWork\MultiColumnWizard\Event\GetOptionsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MigrateRecipientsController
 *
 * @package Avisota\Contao\SubscriptionRecipient\Controller
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class MigrateRecipientsController implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            DcGeneralEvents::ACTION => array(
                array('migrateRecipients'),
            ),

            GetOptionsEvent::NAME => array(
                array('bypassCreateMailingListOptions'),
            ),
        );
    }

    /**
     * @param ActionEvent                   $event
     * @param null                          $eventName
     * @param EventDispatcherInterface|null $eventDispatcher
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
            $input       = $environment->getInputProvider();
            $migrationId = 'AVISOTA_MIGRATE_RECIPIENT_' . $input->getParameter('migration');

            if (!$migrationSettings = $this->getMigrationSettings($migrationId)) {
                return;
            }

            if (!$response = $this->generateResponse($environment, $migrationSettings, $migrationId)) {
                return;
            }

            $event->setResponse($response);
            $event->stopPropagation();
        }
    }

    /**
     * @param $migrationId
     *
     * @return mixed|null
     */
    protected function getMigrationSettings($migrationId)
    {
        global $container;

        $eventDispatcher = $container['event-dispatcher'];

        if (!\Session::getInstance()->get($migrationId)) {
            $addToUrlEvent = new AddToUrlEvent('act=&migration=');
            $eventDispatcher->dispatch(ContaoEvents::BACKEND_ADD_TO_URL, $addToUrlEvent);

            $redirectEvent = new RedirectEvent($addToUrlEvent->getUrl());
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $redirectEvent);

            return null;
        }

        return \Session::getInstance()->get($migrationId);
    }

    /**
     * @param $migrationSettings
     *
     * @return array
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function getMigrationStatement($migrationSettings)
    {
        global $container;

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $container['doctrine.connection.default'];

        $offset   = isset($migrationSettings['offset']) ? $migrationSettings['offset'] : 0;
        $skipped  = isset($migrationSettings['skipped']) ? $migrationSettings['skipped'] : 0;
        $migrated = isset($migrationSettings['migrated']) ? $migrationSettings['migrated'] : 0;

        list($channels, $channelMailingListMapping) = $this->getChannelsAndMailingListMapping($migrationSettings);

        $queryBuilder = $connection->createQueryBuilder();
        /** @var \PDOStatement $stmt */
        $statement = $queryBuilder
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

        return array(
            $offset,
            $skipped,
            $migrated,
            $channelMailingListMapping,
            $statement
        );
    }

    /**
     * @param $migrationSettings
     *
     * @return array
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function getChannelsAndMailingListMapping($migrationSettings)
    {
        global $container;

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $container['doctrine.connection.default'];

        $mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');

        $channels                  = array();
        $channelMailingListMapping = array();
        foreach ($migrationSettings['channels'] as $channel) {
            $mailingList                         = $channel['mailingList'];
            $channel                             = $channel['channel'];
            $channels[]                          = $connection->quote($channel);
            $channelMailingListMapping[$channel] = $mailingListRepository->find($mailingList);
        }

        return array($channels, $channelMailingListMapping);
    }

    /**
     * @param EnvironmentInterface $environment
     * @param                      $migrationSettings
     * @param                      $migrationId
     *
     * @return null|string
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function generateResponse(EnvironmentInterface $environment, $migrationSettings, $migrationId)
    {
        global $container;

        $eventDispatcher = $container['event-dispatcher'];

        $translator          = $environment->getTranslator();
        $entityManager       = EntityHelper::getEntityManager();
        $recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');

        list($offset, $skipped, $migrated, $channelMailingListMapping, $statement) =
            $this->getMigrationStatement($migrationSettings);

        /** @var SubscriptionManager $subscriptionManager */
        $subscriptionManager = $container['avisota.subscription'];
        $subscribeOptions    = 0;

        if ($migrationSettings['ignoreBlacklist']) {
            $subscribeOptions |= SubscriptionManager::OPT_IGNORE_BLACKLIST;
        }

        $user     = \BackendUser::getInstance();
        $response = new \StringBuilder();
        $this->addHeaderContent($response, $translator);

        $contaoRecipients = $statement->fetchAll();
        foreach ($contaoRecipients as $contaoRecipientData) {
            $recipient = $recipientRepository->findOneBy(array('email' => $contaoRecipientData['email']));

            if (!$recipient) {
                $this->addCreateRecipientInformation($response, $contaoRecipientData, $translator);

                $recipient = new Recipient();
                $recipient->setEmail($contaoRecipientData['email']);
                $recipient->setAddedById($user->id);
                $recipient->setAddedByName($user->name);
                $recipient->setAddedByUsername($user->username);
            } else {
                if (!$migrationSettings['overwrite']) {

                    $skipped++;
                    continue;
                } else {
                    $this->addSkippedRecipientInformation($response, $contaoRecipientData, $translator);
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
            $this->migrationFinished($migrationId, $migrated, $skipped);

            return null;
        } else {
            $offset += count($contaoRecipients);
            $this->updateRedirectSession($migrationId, $offset, $skipped, $migrated);
            $this->addReloadScriptAndButton($response, $translator);

            return $response->__toString();
        }
    }

    /**
     * @param $response
     * @param $contaoRecipientData
     * @param $translator
     */
    protected function addCreateRecipientInformation(&$response, $contaoRecipientData, $translator)
    {
        /** @var \StringBuilder $response */
        $response->append('<li>');
        $response->append(
            sprintf(
                $translator->translate('created', 'mem_avisota_recipient_migrate'),
                $contaoRecipientData['email']
            )
        );
        $response->append('</li>');
    }

    /**
     * @param $response
     * @param $contaoRecipientData
     * @param $translator
     */
    protected function addSkippedRecipientInformation(&$response, $contaoRecipientData, $translator)
    {
        $response->append('<li>');
        $response->append(
            sprintf(
                $translator->translate('skipped', 'mem_avisota_recipient_migrate'),
                $contaoRecipientData['email']
            )
        );
        $response->append('</li>');
    }

    /**
     * @param $response
     * @param $translator
     */
    protected function addHeaderContent(&$response, $translator)
    {
        /** @var \StringBuilder $response */
        $response->append('<div class="tl_buttons">&nbsp;</div>');
        $response->append('<h2 class="sub_headline">');
        $response->append(
            $translator->translate('running', 'mem_avisota_recipient_migrate')
        );
        $response->append('</h2>');
        $response->append('<div class="tl_formbody_edit"><ul>');
    }

    /**
     * @param $response
     * @param $translator
     */
    protected function addReloadScriptAndButton(&$response, $translator)
    {
        /** @var \StringBuilder $response */
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
        $response->append(
            $translator->translate('reload', 'mem_avisota_recipient_migrate')
        );
        $response->append('</button></p>');

        $response->append('</div>');
    }

    /**
     * @param $migrationId
     * @param $offset
     * @param $skipped
     * @param $migrated
     */
    protected function updateRedirectSession($migrationId, $offset, $skipped, $migrated)
    {
        $migrationSettings['offset']   = $offset;
        $migrationSettings['skipped']  = $skipped;
        $migrationSettings['migrated'] = $migrated;
        \Session::getInstance()->set($migrationId, $migrationSettings);
    }

    /**
     * @param $migrationId
     * @param $migrated
     * @param $skipped
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function migrationFinished($migrationId, $migrated, $skipped)
    {
        global $container,
               $TL_LANG;

        $eventDispatcher = $container['event-dispatcher'];

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

        $options = $optionsBuilder->getMailingListOptions();
        $event->setOptions($options);
    }
}
