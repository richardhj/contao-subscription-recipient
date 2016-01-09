<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2015 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

global $TL_LANG;

if (!array_key_exists('fe_avisota_subscription', $TL_LANG)) {
    $TL_LANG['fe_avisota_subscription'] = array();
}

$feAvisotaSubscription = array(
    'subscribe'
    => 'Subscribe',
    'unsubscribe'
    => 'Unsubscribe',
    'subscribed'
    => 'Thank you very much, you are now subscribed. Please check your inbox for confirmation email.',
    'allreadySubscribed'
    => 'Thank you very much, but you are already subscribed to our newsletter.',
    'subscribeConfirmation'
    => 'Your subscription was successfully activated.',
    'unsubscribed'
    => 'You\'r now unsubscribed from our newsletter.',
    'notSubscribed'
    => 'You\'r not subscribed to our newsletter.',
    'confirm'
    => 'confirm subscriptions',
    'manage_subscription'
    => 'Manage your subscription',
    'unsubscribe_direct'
    => 'Unsubscribe',
);

$TL_LANG['fe_avisota_subscription'] = array_merge(
    $TL_LANG['fe_avisota_subscription'],
    $feAvisotaSubscription
);
