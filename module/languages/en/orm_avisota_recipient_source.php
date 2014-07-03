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
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipients']                          = array(
	'Select recipient&hellip;',
	'Please choose how recipients are selected.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsManageSubscriptionPage']    = array(
	'Subscription management page',
	'Please choose the subscription management page.'
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
