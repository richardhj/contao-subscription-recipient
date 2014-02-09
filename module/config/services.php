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


/**
 * Define subscription managers
 */

$container['avisota.subscription.recipient'] = $container->share(
	function ($container) {
		return new \Avisota\Contao\Core\Subscription\RecipientSubscriptionManager();
	}
);

$container['avisota.subscription.managers']->append('avisota.subscription.recipient');
