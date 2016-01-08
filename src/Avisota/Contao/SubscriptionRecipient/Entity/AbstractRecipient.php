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

namespace Avisota\Contao\SubscriptionRecipient\Entity;

use Avisota\Contao\Subscription\SubscriptionRecipientInterface;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\Annotation\Accessor;
use Contao\Doctrine\ORM\EntityHelper;

abstract class AbstractRecipient implements SubscriptionRecipientInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);
        $this->email = EntityHelper::callSetterCallbacks($this, static::TABLE_NAME, 'email', $email);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDetails()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        $getter = 'get' . ucfirst($name);
        return $this->$getter();
    }

    /**
     * {@inheritdoc}
     *
     * @Accessor(ignore=true)
     */
    public function getDetails()
    {
        /** @var EntityAccessor $entityAccessor */
        $entityAccessor = $GLOBALS['container']['doctrine.orm.entityAccessor'];
        $details        = $entityAccessor->getPublicProperties($this, true);
        return $details;
    }

    /**
     * {@inheritdoc}
     *
     * @Accessor(ignore=true)
     */
    public function getKeys()
    {
        return array_keys($this->getDetails());
    }
}
