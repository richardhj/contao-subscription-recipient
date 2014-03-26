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

use Avisota\Contao\Entity\MailingList;
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
	 * @var MailingList[]
	 */
	protected $filteredMailingLists = array();

	/**
	 * @var array
	 */
	protected $filteredProperties = array();

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
		$query      = $queryBuilder->getQuery();
		$recipients = $query->getResult();

		$entityAccessor = EntityHelper::getEntityAccessor();

		$mutableRecipients = array();

		/** @var Recipient $recipient */
		foreach ($recipients as $recipient) {
			$mutableRecipients[] = new MutableRecipient(
				$recipient->getEmail(),
				$entityAccessor->getPublicProperties($recipient, true)
			);
		}

		return $mutableRecipients;
	}

	protected function prepareQuery(QueryBuilder $queryBuilder)
	{
		$expr = $queryBuilder->expr();

		if (count($this->filteredMailingLists)) {
			$queryBuilder->innerJoin('r.subscriptions', 's');

			$or = $expr->orX();
			foreach ($this->filteredMailingLists as $index => $mailingList) {
				$or->add($expr->eq('s.mailingList', ':mailingList' . $index));
				$queryBuilder->setParameter('mailingList' . $index, $mailingList->getId());
			}

			$queryBuilder->andWhere($or);
		}

		if (count($this->filteredProperties)) {
			foreach ($this->filteredProperties as $index => $filteredProperty) {
				$property   = 'r.' . $filteredProperty['recipientsPropertyFilter_property'];
				$comparator = $filteredProperty['recipientsPropertyFilter_comparator'];
				$value      = $filteredProperty['recipientsPropertyFilter_value'];

				switch ($comparator) {
					case 'empty':
						$queryBuilder->andWhere(
							$expr->orX(
								$expr->eq($property, ''),
								$expr->isNull($property)
							)
						);
						break;

					case 'not empty':
						$queryBuilder->andWhere(
							$expr->gt($property, '')
						);
						break;

					case 'eq':
						$queryBuilder->andWhere(
							$expr->eq($property, ':property' . $index)
						);
						$queryBuilder->setParameter(
							':property' . $index,
							$value
						);
						break;

					case 'neq':
						$queryBuilder->andWhere(
							$expr->neq($property, ':property' . $index)
						);
						$queryBuilder->setParameter(
							':property' . $index,
							$value
						);
						break;

					case 'gt':
						$queryBuilder->andWhere(
							$expr->gt($property, ':property' . $index)
						);
						$queryBuilder->setParameter(
							':property' . $index,
							$value
						);
						break;

					case 'gte':
						$queryBuilder->andWhere(
							$expr->gte($property, ':property' . $index)
						);
						$queryBuilder->setParameter(
							':property' . $index,
							$value
						);
						break;

					case 'lt':
						$queryBuilder->andWhere(
							$expr->lt($property, ':property' . $index)
						);
						$queryBuilder->setParameter(
							':property' . $index,
							$value
						);
						break;

					case 'lte':
						$queryBuilder->andWhere(
							$expr->lte($property, ':property' . $index)
						);
						$queryBuilder->setParameter(
							':property' . $index,
							$value
						);
						break;
				}
			}
		}
	}

	/**
	 * @param MailingList[] $filteredMailingLists
	 */
	public function setFilteredMailingLists(array $filteredMailingLists)
	{
		$this->filteredMailingLists = array_values($filteredMailingLists);
		return $this;
	}

	/**
	 * @return \Avisota\Contao\Entity\MailingList[]
	 */
	public function getFilteredMailingLists()
	{
		return $this->filteredMailingLists;
	}

	/**
	 * @param array $filteredProperties
	 */
	public function setFilteredProperties(array $filteredProperties)
	{
		$this->filteredProperties = $filteredProperties;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getFilteredProperties()
	{
		return $this->filteredProperties;
	}
}
