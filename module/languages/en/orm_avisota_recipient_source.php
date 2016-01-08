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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsManageSubscriptionPage']    = array(
    'Subscription management page',
    'Please choose the subscription management page.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsUnsubscribePage']           = array(
    'Unsubscribe page',
    'Please choose the page for direct unsubscription.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetails']                   = array(
    'Fetch details from&hellip;',
    'Please choose where the details should be fetched from.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsUsePropertyFilter']         = array(
    'Filter by properties',
    'Filter recipients by properties and values.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter']            = array(
    'Properties filter',
    'Filter the recipients by property values.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_property']   = array('Column');
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparator'] = array('Comparator');
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_value']      = array('Value');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipients_legend'] = 'Recipient settings';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipients'] = array(
    'Integrated Recipients',
    'Use the integrated recipient management.'
);

$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators']['empty']     = 'is empty';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators']['not_empty'] = 'is not empty';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators']['eq']        = '==';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators']['neq']       = '!=';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators']['gt']        = '>';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators']['gte']       = '>=';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators']['lt']        = '<';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsPropertyFilter_comparators']['lte']       = '<=';

$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetailsTypes']['recipients_details']        = 'only provided details';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetailsTypes']['member_details']            = 'only member details';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetailsTypes']['recipients_member_details'] = 'mix provided with member details';
