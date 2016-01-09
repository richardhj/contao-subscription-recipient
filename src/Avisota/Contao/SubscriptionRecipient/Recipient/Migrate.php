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

namespace Avisota\Contao\SubscriptionRecipient\Recipient;

use Avisota\Contao\SubscriptionRecipient\Event\MigrateRecipientEvent;
use Doctrine\DBAL\Driver\PDOStatement;

/**
 * Class Migrate
 *
 * @package Avisota\Contao\SubscriptionRecipient\Recipient
 */
class Migrate extends \Controller
{
    /**
     * @param MigrateRecipientEvent $event
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public static function collectPersonalsFromMembers(MigrateRecipientEvent $event)
    {
        global $container,
               $TL_DCA;

        $migrate = new Migrate();
        $migrate->loadLanguageFile('orm_avisota_recipient');
        $migrate->loadDataContainer('orm_avisota_recipient');

        $migrationSettings = $event->getMigrationSettings();

        if ($migrationSettings['importFromMembers']) {
            /** @var \Doctrine\DBAL\Connection $connection */
            $connection = $container['doctrine.connection.default'];

            $recipient = $event->getRecipient();

            $queryBuilder = $connection->createQueryBuilder();
            /** @var PDOStatement $stmt */
            $stmt   = $queryBuilder
                ->select('*')
                ->from('tl_member', 'm')
                ->where(
                    $queryBuilder
                        ->expr()
                        ->eq('email', $recipient->getEmail())
                )
                ->execute();
            $member = $stmt->fetch();

            $fields = $TL_DCA['orm_avisota_recipient']['fields'];
            foreach ($fields as $fieldName => $fieldConfig) {
                if (isset($fieldConfig['eval']['migrateFrom'])) {
                    $recipient->$fieldName = $member[$fieldConfig['eval']['migrateFrom']];
                }
            }
        }
    }
}
