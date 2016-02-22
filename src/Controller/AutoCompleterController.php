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
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use ContaoCommunityAlliance\DcGeneral\Factory\DcGeneralFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AutoCompleterController
 *
 * @package Avisota\Contao\SubscriptionRecipient\Controller
 */
class AutoCompleterController implements EventSubscriberInterface
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
                array('injectAutocompleter'),
            ),
        );
    }

    /**
     * @param ActionEvent                   $event
     * @param null                          $eventName
     * @param EventDispatcherInterface|null $eventDispatcher
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
            // TODO outsource in template
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
}
