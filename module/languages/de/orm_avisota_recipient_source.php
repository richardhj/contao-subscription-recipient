<?php
/**
 * Translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 *
 * @link    http://help.transifex.com/intro/translating.html
 * @link    https://www.transifex.com/projects/p/avisota-contao/language/de/
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *
 * last-updated: 2014-03-25T14:15:15+01:00
 */

global $TL_LANG;

$ormAvisotaRecipientSource = array(
    'recipients_legend' => 'Empfängereinstellungen',

    'recipients_details' => 'Nur eingegebene Details',

    'recipients_member_details' => 'Eingegebene mit Mitgliederdetails mischen',

    'member_details' => 'Nur Mitgliederdetails',

    'recipients' => array(
        'Integrierte Empfänger',
        'Das in Avisota integrierte Empfängermanagement benutzen.',
    ),

    'recipientsDetails' => array(
        'Details zusammenführen aus &hellip;',
        'Bitte wählen Sie aus, von woher die Details zusammengeführt werden sollen.',
    ),

    'recipientsDetailsTypes' => array(
        'member_details'            => 'Nur Mitgliederdetails',
        'recipients_details'        => 'Nur eingegebene Details',
        'recipients_member_details' => 'Eingegebene mit Mitgliederdetails mischen',
    ),

    'recipientsManageSubscriptionPage' => array(
        'Verwaltungsseite',
        'Bitte wählen Sie die Seite aus, auf der das Abonnement verwalten wird.',
    ),

    'recipientsPropertyFilter' => array(
        'Eigenschaften-Filter',
        'Abonnenten nach Eigenschaften filtern.',
    ),

    'recipientsPropertyFilter_comparator' => array(
        'Vergleichsoperand',
    ),

    'recipientsPropertyFilter_comparators' => array(
        'Vergleichsoperand',
        'empty'     => 'Ist leer',
        'eq'        => '==',
        'gt'        => '>',
        'gte'       => '>=',
        'lt'        => '<',
        'lte'       => '<=',
        'neq'       => '!=',
        'not_empty' => 'Ist nicht leer',
    ),

    'recipientsPropertyFilter_property' => array(
        'Spalte',
    ),

    'recipientsPropertyFilter_value' => array(
        'Wert',
    ),

    'recipientsUnsubscribePage' => array(
        'Abmeldeseite',
        'Bitte wählen Sie die Seite aus, die für direkte Abbestellungen benutzt werden soll.',
    ),

    'recipientsUsePropertyFilter' => array(
        'Nach Eigenschaft Filtern',
        'Abonnenten nach Eigenschaften filtern.',
    ),
);

$TL_LANG['orm_avisota_recipient_source'] = array_merge(
    $TL_LANG['orm_avisota_recipient_source'],
    $ormAvisotaRecipientSource
);
