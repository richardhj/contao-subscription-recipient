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

namespace Avisota\Contao\SubscriptionRecipient\RecipientSource;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Recipient\MutableRecipient;
use Avisota\RecipientSource\RecipientSourceInterface;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AvisotaRecipientSourceIntegratedRecipients
 * @SuppressWarnings(PHPMD.LongVariable)
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
     * @var string
     */
    protected $manageSubscriptionUrlPattern = null;

    /**
     * @var string
     */
    protected $unsubscribeUrlPattern = null;

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
     * @param null $limit
     * @param null $offset
     *
     * @return array
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getRecipients($limit = null, $offset = null)
    {
        global $container,
               $TL_LANG;

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

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        /** @var Recipient $recipient */
        foreach ($recipients as $recipient) {
            $properties = $entityAccessor->getPublicProperties($recipient, true);

            if ($this->manageSubscriptionUrlPattern) {
                $loadLanguageEvent = new LoadLanguageFileEvent('fe_avisota_subscription');
                $eventDispatcher->dispatch(ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE, $loadLanguageEvent);

                $url = $this->manageSubscriptionUrlPattern;
                $url = preg_replace_callback(
                    '~##([^#]+)##~',
                    function ($matches) use ($properties) {
                        if (isset($properties[$matches[1]])) {
                            return $properties[$matches[1]];
                        }
                        return $matches[0];
                    },
                    $url
                );

                $properties['manage_subscription_link'] = array(
                    'url'  => $url,
                    'text' => &$TL_LANG['fe_avisota_subscription']['manage_subscription']
                );
            }

            if ($this->unsubscribeUrlPattern) {
                $loadLanguageEvent = new LoadLanguageFileEvent('fe_avisota_subscription');
                $eventDispatcher->dispatch(ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE, $loadLanguageEvent);

                $url = $this->unsubscribeUrlPattern;
                $url = preg_replace_callback(
                    '~##([^#]+)##~',
                    function ($matches) use ($properties) {
                        if (isset($properties[$matches[1]])) {
                            return $properties[$matches[1]];
                        }
                        return $matches[0];
                    },
                    $url
                );

                $properties['unsubscribe_link'] = array(
                    'url'  => $url,
                    'text' => &$TL_LANG['fe_avisota_subscription']['unsubscribe_direct']
                );
            }

            $mutableRecipients[] = new MutableRecipient(
                $recipient->getEmail(),
                $properties
            );
        }

        return $mutableRecipients;
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    protected function prepareQuery(QueryBuilder $queryBuilder)
    {
        $expr = $queryBuilder->expr();

        if (count($this->filteredMailingLists)) {
            $queryBuilder->innerJoin('r.subscriptions', 's');

            $orExpression = $expr->orX();
            foreach ($this->filteredMailingLists as $index => $mailingList) {
                $orExpression->add($expr->eq('s.mailingList', ':mailingList' . $index));
                $queryBuilder->setParameter('mailingList' . $index, $mailingList->getId());
            }

            $queryBuilder->andWhere($orExpression);
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
                                $expr->eq($property, ':property' . $index),
                                $expr->isNull($property)
                            )
                        );
                        $value = '';
                        break;

                    case 'not empty':
                        $queryBuilder->andWhere(
                            $expr->gt($property, ':property' . $index)
                        );
                        $value = '';
                        break;

                    case 'eq':
                        $queryBuilder->andWhere(
                            $expr->eq($property, ':property' . $index)
                        );
                        break;

                    case 'neq':
                        $queryBuilder->andWhere(
                            $expr->neq($property, ':property' . $index)
                        );
                        break;

                    case 'gt':
                        $queryBuilder->andWhere(
                            $expr->gt($property, ':property' . $index)
                        );
                        break;

                    case 'gte':
                        $queryBuilder->andWhere(
                            $expr->gte($property, ':property' . $index)
                        );
                        break;

                    case 'lt':
                        $queryBuilder->andWhere(
                            $expr->lt($property, ':property' . $index)
                        );
                        break;

                    case 'lte':
                        $queryBuilder->andWhere(
                            $expr->lte($property, ':property' . $index)
                        );
                        break;
                }

                $queryBuilder->setParameter(
                    ':property' . $index,
                    $value
                );
            }
        }
    }

    /**
     * @param MailingList[] $filteredMailingLists
     *
     * @return $this
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
     *
     * @return $this
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

    /**
     * @return string
     */
    public function getManageSubscriptionUrlPattern()
    {
        return $this->manageSubscriptionUrlPattern;
    }

    /**
     * @param string $manageSubscriptionUrlPattern
     *
     * @return RecipientsRecipientSource
     */
    public function setManageSubscriptionUrlPattern($manageSubscriptionUrlPattern)
    {
        $this->manageSubscriptionUrlPattern =
            empty($manageSubscriptionUrlPattern) ? null : (string) $manageSubscriptionUrlPattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnsubscribeUrlPattern()
    {
        return $this->unsubscribeUrlPattern;
    }

    /**
     * @param string $unsubscribeUrlPattern
     *
     * @return RecipientsRecipientSource
     */
    public function setUnsubscribeUrlPattern($unsubscribeUrlPattern)
    {
        $this->unsubscribeUrlPattern =
            empty($unsubscribeUrlPattern) ? null : (string) $unsubscribeUrlPattern;

        return $this;
    }
}
