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
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipients']                              = array(
	'Select recipient&hellip;',
	'Please choose how recipients are selected.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsManageSubscriptionPage'] = array(
	'Subscription management page',
	'Please choose the subscription management page.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsMailingLists']          = array(
	'Mailing lists',
	'Please choose the selected mailing lists. Only recipients that subscribe selected mailing lists are available.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsAllowSingleListSelection']        = array(
	'Allow single select mailing lists',
	'Allow the writer to single select mailing lists from this recipient source.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsAllowSingleSelection']            = array(
	'Allow single select recipients',
	'Allow the writer to single select recipients from this recipient source.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsDetails']                         = array(
	'Fetch details from&hellip;',
	'Please choose where the details should be fetched from.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsFilterByColumns']                 = array(
	'Column filter',
	'Filter the recipients by columns.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsFilterByColumnsField']            = array('Column');
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsFilterByColumnsComparator']       = array('Comparator');
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsFilterByColumnsValue']            = array('Value');
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipientsFilterByColumnsNoEscape']         = array(
	'SQL',
	'Use value as native SQL (&rarr; the value will not excaped).'
);

$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipients_details'] = 'only provided details';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['member_details'] = 'only member details';
$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['recipients_member_details'] = 'mix provided with member details';