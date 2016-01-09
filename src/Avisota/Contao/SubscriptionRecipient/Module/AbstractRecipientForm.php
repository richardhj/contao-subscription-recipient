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

use Avisota\Contao\Entity\MailingList;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Haste\Form\Form;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ModuleAvisotaRecipientForm
 */
abstract class AbstractRecipientForm extends \TwigModule
{
    public function __construct($module)
    {
        global $container;

        parent::__construct($module);

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

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
        // Todo if the variable $mailingLists in use
        #$mailingLists          = array();
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
        global $TL_DCA;

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
            if (isset($TL_DCA['orm_avisota_recipient']['fields'][$availableFieldName])) {
                $dca = $TL_DCA['orm_avisota_recipient']['fields'][$availableFieldName];

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
