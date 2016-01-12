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

namespace Avisota\Contao\SubscriptionRecipient\DataContainer\DataProvider;


use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\SubscriptionRecipient\Event\ExportRecipientPropertyEvent;

use Avisota\Contao\SubscriptionRecipient\RecipientEvents;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\EntityHelper;


use ContaoCommunityAlliance\DcGeneral\Data\DefaultModel;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\Data\NoOpDataProvider;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class RecipientExportDataProvider
 *
 * @package Avisota\Contao\SubscriptionRecipient\DataContainer\DataProvider
 */
class RecipientExportDataProvider extends NoOpDataProvider
{
    const SESSION_NAME = 'AVISOTA_RECIPIENT_EXPORT_SETTINGS';

    /**
     * @param ModelInterface $objItem
     *
     * @return ModelInterface|void
     */
    public function save(ModelInterface $objItem)
    {
        $exportSettings = $objItem->getPropertiesAsArray();

        $session             = \Session::getInstance();
        $recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');

        $session->set(static::SESSION_NAME, $exportSettings);

        switch ($exportSettings['delimiter']) {
            case 'semicolon':
                $delimiter = ';';
                break;
            case 'space':
                $delimiter = ' ';
                break;
            case 'tabulator':
                $delimiter = "\t";
                break;
            case 'linebreak':
                $delimiter = "\n";
                break;
            default:
                $delimiter = ',';
        }

        switch ($exportSettings['enclosure']) {
            case 'single':
                $enclosure = "'";
                break;
            default:
                $enclosure = '"';
        }

        $length     = 0;
        $csv        = tmpfile();
        $recipients = $recipientRepository->findAll();

        /** @var Recipient $recipient */
        foreach ($recipients as $recipient) {
            $row = $this->generateCSVRows($recipient, $exportSettings);

            $length += fputcsv($csv, $row, $delimiter, $enclosure);
        }

        if (!headers_sent()) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Length: ' . $length);
            header('Content-Disposition: attachment; filename="export.csv"');
        }

        rewind($csv);
        fpassthru($csv);
        fclose($csv);
        exit;
    }

    /**
     * @param $recipient
     * @param $exportSettings
     *
     * @return array
     */
    protected function generateCSVRows($recipient, $exportSettings)
    {
        global $container;

        /** @var EntityAccessor $entityAccessor */
        $entityAccessor = $container['doctrine.orm.entityAccessor'];

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];

        $propertyNames = $exportSettings['columns'];

        $row = array();
        foreach ($propertyNames as $propertyName) {
            if ($entityAccessor->hasProperty($recipient, $propertyName)) {
                $value = $entityAccessor->getProperty($recipient, $propertyName);
            } else {
                $value = null;
            }

            $string = null;
            if (is_resource($value)) {
                $string = stream_get_contents($value);
            } else {
                if (is_object($value)) {
                    if (method_exists($value, '__toString')) {
                        $string = (string) $value;
                    }
                } else {
                    if (is_scalar($value)) {
                        $string = (string) $value;
                    }
                }
            }

            $event = new ExportRecipientPropertyEvent($recipient, $propertyName, $value, $string);
            $eventDispatcher->dispatch(RecipientEvents::EXPORT_RECIPIENT_PROPERTY, $event);

            $row[] = $event->getString();
        }

        return $row;
    }

    /**
     * @return DefaultModel|ModelInterface
     */
    public function getEmptyModel()
    {
        $session        = \Session::getInstance();
        $exportSettings = $session->get(static::SESSION_NAME);

        $model = parent::getEmptyModel();

        if ($exportSettings && is_array($exportSettings)) {
            $model->setPropertiesAsArray($exportSettings);
        }

        return $model;
    }
}
