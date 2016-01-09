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

namespace Avisota\Contao\SubscriptionRecipient\Module;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ModuleAvisotaSubscription
 */
class Subscription extends AbstractRecipientForm
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_avisota_subscription';

    public function __construct($module)
    {
        global $container;

        parent::__construct($module);

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('avisota_subscription')
        );
    }

    /**
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template           = new BackendTemplate('be_wildcard');
            $template->wildcard = '### Avisota subscription module ###';
            return $template->parse();
        }

        $this->formTemplate = $this->avisota_template_subscription;

        return parent::generate();
    }

    /**
     * Generate the content element
     */
    public function compile()
    {
        if ($this->Input->post('FORM_SUBMIT') == $this->formName) {
            $this->avisota_recipient_fields = array();
        }

        $this->addForm();
    }

    protected function submit(array $recipientData, array $mailingLists, FrontendTemplate $template)
    {
        if ($this->Input->post('subscribe')) {
            return $this->handleSubscribeSubmit($recipientData, $mailingLists, $template);
        }
        if ($this->Input->post('unsubscribe')) {
            return $this->handleUnsubscribeSubmit($recipientData, $mailingLists, $template);
        }
        return null;
    }
}
