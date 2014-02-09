<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\RecipientSource;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\RecipientSource;
use Contao\Doctrine\ORM\EntityHelper;

class IntegratedRecipientsByMailingListFactory implements RecipientSourceFactoryInterface
{
	public function createRecipientSource(RecipientSource $recipientSource)
	{
		return new IntegratedRecipientsByMailingList($recipientSource->getMailingLists());
	}
}
