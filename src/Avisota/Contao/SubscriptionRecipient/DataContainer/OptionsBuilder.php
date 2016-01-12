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

namespace Avisota\Contao\SubscriptionRecipient\DataContainer;

use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\Compatibility\DcCompat;
use ContaoCommunityAlliance\DcGeneral\DC_General;
use ContaoCommunityAlliance\DcGeneral\Factory\DcGeneralFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OptionsBuilder
 *
 * @package Avisota\Contao\SubscriptionRecipient\DataContainer
 */
class OptionsBuilder implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'avisota.create-salutation-recipient-field-options' => 'createRecipientFieldOptions',
            'avisota.create-recipient-field-options'            => 'createRecipientFieldOptions',
            'avisota.create-recipient-options'                  => 'createRecipientOptions',
        );
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createRecipientFieldOptions(CreateOptionsEvent $event)
    {
        $this->getRecipientFieldOptions($event->getDataContainer(), $event->getOptions());
    }

    /**
     * @param array               $options
     * @param DC_General|DcCompat $general
     *
     * @return array
     */
    public function getRecipientFieldOptions(DC_General $general, $options = array())
    {
        global $container;

        //TODO check general parameter
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

        $factory = DcGeneralFactory::deriveFromEnvironment($general->getEnvironment());
        $factory->setContainerName('orm_avisota_recipient');
        $containerFactory = $factory->createContainer();

        if ($containerFactory->hasPropertiesDefinition()) {
            $properties = $containerFactory->getPropertiesDefinition()->getProperties();

            foreach ($properties as $property) {
                if ($property->getWidgetType()) {
                    $options[$property->getName()] = $property->getLabel();
                }
            }
        }

        return $options;
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createRecipientOptions(CreateOptionsEvent $event)
    {
        $this->getRecipientOptions($event->getOptions());
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getRecipientOptions($options = array())
    {
        $recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');
        $recipients          = $recipientRepository->findBy(
            array(),
            array('forename' => 'ASC', 'surname' => 'ASC', 'email' => 'ASC')
        );

        /** @var \Avisota\Contao\Entity\Recipient $recipient */
        foreach ($recipients as $recipient) {
            if ($recipient->getForename() && $recipient->getSurname()) {
                $options[$recipient->getId()] = sprintf(
                    '%s, %s &lt;%s&gt;',
                    $recipient->getSurname(),
                    $recipient->getForename(),
                    $recipient->getEmail()
                );
            } else {
                if ($recipient->getForename()) {
                    $options[$recipient->getId()] = sprintf(
                        '%s &lt;%s&gt;',
                        $recipient->getForename(),
                        $recipient->getEmail()
                    );
                } else {
                    if ($recipient->getSurname()) {
                        $options[$recipient->getId()] = sprintf(
                            '%s &lt;%s&gt;',
                            $recipient->getSurname(),
                            $recipient->getEmail()
                        );
                    } else {
                        $options[$recipient->getId()] = $recipient->getEmail();
                    }
                }
            }
        }
        return $options;
    }
}
