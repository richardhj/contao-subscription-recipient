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

namespace Avisota\Contao\SubscriptionRecipient\Event;

use Avisota\Contao\Entity\Recipient;
use Symfony\Component\EventDispatcher\Event;

class MigrateRecipientEvent extends RecipientAwareEvent
{
    /**
     * @var array
     */
    protected $migrationSettings;

    /**
     * @var array
     */
    protected $contaoRecipientData;

    function __construct(array $migrationSettings, array $contaoRecipientData, Recipient $recipient)
    {
        parent::__construct($recipient);
        $this->migrationSettings   = $migrationSettings;
        $this->contaoRecipientData = $contaoRecipientData;
    }

    /**
     * @return array
     */
    public function getMigrationSettings()
    {
        return $this->migrationSettings;
    }

    /**
     * @return array
     */
    public function getContaoRecipientData()
    {
        return $this->contaoRecipientData;
    }
}
