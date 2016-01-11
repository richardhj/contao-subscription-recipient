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

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetEditModeButtonsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ButtonController
 *
 * @package Avisota\Contao\SubscriptionRecipient\Controller
 */
class ButtonController implements EventSubscriberInterface
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
            GetEditModeButtonsEvent::NAME => array(
                array('getExportButtons'),
                array('getMigrateButtons'),
            ),
        );
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
}
