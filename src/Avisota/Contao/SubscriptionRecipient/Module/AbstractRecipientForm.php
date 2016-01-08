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

namespace Avisota\Contao\SubscriptionRecipient\Module;

use Avisota\Contao\Entity\MailingList;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Haste\Form\Form;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ModuleAvisotaRecipientForm
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-subscription-recipient
 */
abstract class AbstractRecipientForm extends \TwigModule
{
    public function __construct($module)
    {
        parent::__construct($module);

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('fe_avisota_subscription')
        );

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('orm_avisota_recipient')
        );

        $eventDispatcher->dispatch(
            ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
            new LoadDataContainerEvent('orm_avisota_recipient')
        );
    }

    /**
     * Load multiple mailing lists by ID.
     *
     * @param $mailingListIds
     *
     * @return array|MailingList[]
     */
    protected function loadMailingLists($mailingListIds)
    {
        $mailingLists          = array();
        $mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
        $queryBuilder          = $mailingListRepository->createQueryBuilder('ml');
        $expr                  = $queryBuilder->expr();
        $queryBuilder
            ->select('ml')
            ->where($expr->in('ml.id', ':ids'))
            ->setParameter('ids', $mailingListIds);
        $query = $queryBuilder->getQuery();
        /** @var MailingList[] $result */
        return $query->getResult();
    }

    /**
     * Create an options list of mailing lists.
     *
     * @param $mailingListIds
     *
     * @return array|string[]
     */
    protected function loadMailingListOptions($mailingListIds)
    {
        $mailingLists = $this->loadMailingLists($mailingListIds);
        $options      = array();

        foreach ($mailingLists as $mailingList) {
            $options[$mailingList->getId()] = $mailingList->getTitle();
        }

        return $options;
    }

    protected function createForm(array $availableFieldNames, array $values = array())
    {
        $class = new \ReflectionClass($this);

        $form = new Form(
            'avisota_' . strtolower($class->getShortName()) . '_' . $this->id,
            'POST',
            function (Form $haste) {
                return \Input::post('FORM_SUBMIT') === $haste->getFormId();
            },
            (bool) $this->tableless
        );

        foreach ($availableFieldNames as $availableFieldName) {
            if (isset($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'][$availableFieldName])) {
                $dca = $GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'][$availableFieldName];

                if (isset($values[$availableFieldName])) {
                    $dca['value'] = $values[$availableFieldName];
                }

                $form->addFormField($availableFieldName, $dca);
            }
        }

        if ($this->avisota_form_target) {
            $form->setFormActionFromPageId($this->avisota_form_target);
        }

        return $form;
    }
}
