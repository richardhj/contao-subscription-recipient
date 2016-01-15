<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

global $TL_LANG;

$ormAvisotaRecipientSource = array(
    'recipients_legend' =>
        'Recipient settings',

    'recipientsDetailsTypes' => array(
        'recipients_details'        => 'only provided details',
        'member_details'            => 'only member details',
        'recipients_member_details' => 'mix provided with member details',
    ),

    'recipientsManageSubscriptionPage' => array(
        'Subscription management page',
        'Please choose the subscription management page.',
    ),

    'recipientsUnsubscribePage' => array(
        'Unsubscribe page',
        'Please choose the page for direct unsubscription.',
    ),

    'recipientsDetails' => array(
        'Fetch details from&hellip;',
        'Please choose where the details should be fetched from.',
    ),

    'recipientsUsePropertyFilter' => array(
        'Filter by properties',
        'Filter recipients by properties and values.',
    ),

    'recipientsPropertyFilter' => array(
        'Properties filter',
        'Filter the recipients by property values.',
    ),

    'recipientsPropertyFilter_property' => array(
        'Column',
    ),

    'recipientsPropertyFilter_comparator' => array(
        'Comparator',
    ),

    'recipientsPropertyFilter_value' => array(
        'Value',
    ),

    'recipients' => array(
        'Integrated Recipients',
        'Use the integrated recipient management.',
    ),

    'recipientsPropertyFilter_comparators' => array(
        'empty'     => 'is empty',
        'not_empty' => 'is not empty',
        'eq'        => '==',
        'neq'       => '!=',
        'gt'        => '>',
        'gte'       => '>=',
        'lt'        => '<',
        'lte'       => '<=',
    ),
);

$TL_LANG['orm_avisota_recipient_source'] = array_merge(
    $TL_LANG['orm_avisota_recipient_source'],
    $ormAvisotaRecipientSource
);
