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

namespace Avisota\Contao\SubscriptionRecipient\RecipientSource;

use Avisota\Contao\Entity\Recipient;
use Avisota\Recipient\MutableRecipient;
use Avisota\RecipientSource\RecipientSourceInterface;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AvisotaRecipientSourceIntegratedRecipients
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-subscription-recipient
 */
class RecipientsRecipientSource implements RecipientSourceInterface
{
	/**
	 * Count the recipients.
	 *
	 * @return int
	 */
	public function countRecipients()
	{
		$queryBuilder = EntityHelper::getEntityManager()->createQueryBuilder();
		$queryBuilder
			->select('COUNT(r.id)')
			->from('Avisota\Contao:Recipient', 'r');
		$this->prepareQuery($queryBuilder);
		$query = $queryBuilder->getQuery();
		return $query->getResult(Query::HYDRATE_SINGLE_SCALAR);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipients($limit = null, $offset = null)
	{
		$queryBuilder = EntityHelper::getEntityManager()->createQueryBuilder();
		$queryBuilder
			->select('r')
			->from('Avisota\Contao:Recipient', 'r');
		$this->prepareQuery($queryBuilder);
		if ($limit > 0) {
			$queryBuilder->setMaxResults($limit);
		}
		if ($offset > 0) {
			$queryBuilder->setFirstResult($offset);
		}
		$queryBuilder->orderBy('r.email');
		$query = $queryBuilder->getQuery();
		$integratedRecipients = $query->getResult();

		$recipients = array();

		/** @var Recipient $integratedRecipient */
		foreach ($integratedRecipients as $integratedRecipient) {
			$recipients[] = new MutableRecipient(
				$integratedRecipient->getEmail(),
				$integratedRecipient->toArray()
			);
		}

		return $recipients;
	}
}
