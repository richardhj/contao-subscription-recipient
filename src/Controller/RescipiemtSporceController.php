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

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Factory\DcGeneralFactory;
use MenAtWork\MultiColumnWizard\Event\GetOptionsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RescipiemtSporceController
 *
 * @package Avisota\Contao\SubscriptionRecipient\Controller
 */
class RescipiemtSporceController implements EventSubscriberInterface
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
            GetOptionsEvent::NAME => array(
                array('bypassCreateRecipientPropertiesOptions'),
            ),
        );
    }

    /**
     * @param GetOptionsEvent $event
     */
    public function bypassCreateRecipientPropertiesOptions(GetOptionsEvent $event)
    {
        if (($event->getModel()->getProviderName() === 'orm_avisota_recipient_source'
             && $event->getPropertyName() != 'recipientsPropertyFilter')
            || $event->getSubPropertyName() != 'recipientsPropertyFilter_property'
        ) {
            return;
        }

        $options = $event->getOptions();
        $options = $this->getRecipientPropertiesOptions($event->getEnvironment(), $options);
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

        if (!is_array($options)) {
            $options = (array) $options;
        }

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $loadDataContainerEvent = new LoadDataContainerEvent('orm_avisota_recipient');
        $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER, $loadDataContainerEvent);

        $loadLanguageFileEvent = new LoadLanguageFileEvent('orm_avisota_recipient');
        $eventDispatcher->dispatch(ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE, $loadLanguageFileEvent);

        $dcGeneralFactory = DcGeneralFactory::deriveFromEnvironment($environment);

        $dcGeneralFactory->setContainerName('orm_avisota_recipient');
        $containerFactory = $dcGeneralFactory->createContainer();
        $properties       = $containerFactory->getPropertiesDefinition()->getProperties();

        foreach ($properties as $property) {
            $options[$property->getName()] = $property->getLabel() ?: $property->getName();
        }

        return $options;
    }
}
