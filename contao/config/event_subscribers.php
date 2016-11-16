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

use Avisota\Contao\SubscriptionRecipient\Controller\AutoCompleterController;
use Avisota\Contao\SubscriptionRecipient\Controller\ButtonController;
use Avisota\Contao\SubscriptionRecipient\Controller\DataContainerController;
use Avisota\Contao\SubscriptionRecipient\Controller\DoctrineManagerController;
use Avisota\Contao\SubscriptionRecipient\Controller\MigrateRecipientsController;
use Avisota\Contao\SubscriptionRecipient\Controller\RecipientTokenController;
use Avisota\Contao\SubscriptionRecipient\DataContainer\OptionsBuilder;
use Avisota\Contao\SubscriptionRecipient\Controller\RecipientController;
use Avisota\Contao\SubscriptionRecipient\Controller\RescipiemtSporceController;
use Avisota\Contao\SubscriptionRecipient\Controller\SubscriptionController;
use Avisota\Contao\SubscriptionRecipient\DataContainer\Recipient;
use Avisota\Contao\SubscriptionRecipient\DataContainer\RecipientSource;

return array(
    new OptionsBuilder(),
    new RecipientController(),
    new RescipiemtSporceController(),
    new SubscriptionController(),
    new DataContainerController(),
    new ButtonController(),
    new AutoCompleterController(),
    new DoctrineManagerController(),
    new MigrateRecipientsController(),
    new RecipientTokenController(),
    new Recipient(),
    new RecipientSource()
);
