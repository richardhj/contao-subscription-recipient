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

/**
 * Class AbstractRecipient
 *
 * @package Avisota\Contao\SubscriptionRecipient\Entity
 */
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
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);
        $this->email = EntityHelper::callSetterCallbacks($this, static::TABLE_NAME, 'email', $email);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasDetails()
    {
        return true;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function get($name)
    {
        $getter = 'get' . ucfirst($name);
        return $this->$getter();
    }

    /**
     * @Accessor(ignore=true)
     *
     * @return mixed
     */
    public function getDetails()
    {
        global $container;

        /** @var EntityAccessor $entityAccessor */
        $entityAccessor = $container['doctrine.orm.entityAccessor'];
        $details        = $entityAccessor->getPublicProperties($this, true);
        return $details;
    }

    /**
     * @Accessor(ignore=true)
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->getDetails());
    }
}
