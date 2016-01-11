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

namespace Avisota\Contao\SubscriptionRecipient\Controller;

use Avisota\Contao\SubscriptionRecipient\RecipientDataContainerEvents;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Factory\DcGeneralFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DataContainerController
 *
 * @package Avisota\Contao\SubscriptionRecipient\Controller
 */
class DataContainerController implements EventSubscriberInterface
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
        );
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
}
