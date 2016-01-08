<?php
/**
 * Translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 *
 * @link    http://help.transifex.com/intro/translating.html
 * @link    https://www.transifex.com/projects/p/avisota-contao/language/rm/
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *
 * last-updated: 2014-03-25T14:15:15+01:00
 */

global $TL_LANG;

$ormAvisotaRecipientSource = array(
    'recipients_legend' => 'Configuraziun dals destinaturs',

    'member_details' => 'be detagls dal commember',

    'recipients_details' => 'be detagls inditgads',

    'recipients_member_details' => 'cumbinar detagls inditgads cun detagls dal commember',

    'recipients' => array(
        'Destinaturs integrads',
        'Utilisar l\'adminisitraziun da destinaturs integrada.',
    ),

    'recipientsAllowSingleListSelection' => array(
        'Lubir da tscherner be ina glista da mail',
        'Lubir a l\'autur da tscherner be ina glista da mail da questa funtauna da destinaturs.',
    ),

    'recipientsAllowSingleSelection' => array(
        'Lubir da tscherner be in destinatur',
        'Lubir a l\'autur da be tscherner in destinatur da questa funtauna da destinaturs.',
    ),

    'recipientsDetails' => array(
        'Retschaiver ils detagls da&hellip;',
        'Tscherne danunder ch\'ils detagls duain vegnir retschavids.',
    ),

    'recipientsDetailsTypes' => array(
        'member_details'            => 'be detagls dal commember',
        'recipients_details'        => 'be detagls inditgads',
        'recipients_member_details' => 'cumbinar detagls inditgads cun detagls dal commember',
    ),

    'recipientsFilterByColumns' => array(
        'Filter da colonnas',
        'Filtrar ils destinatur tenor colonnas.',
    ),

    'recipientsFilterByColumnsComparator' => array(
        'Cumparegliader',
    ),

    'recipientsFilterByColumnsField' => array(
        'Colonna',
    ),

    'recipientsFilterByColumnsNoEscape' => array(
        'SQL',
        'Utilisar SQL navig (&rarr; la valur na vegn betg controllada!).',
    ),

    'recipientsFilterByColumnsValue' => array(
        'Valur',
    ),

    'recipientsMailingLists' => array(
        'Glistas da mail',
        'Tscherna las glistas da mail. Be destinaturs che abuneschan las glistas da mail tschernidas èn disponibels.',
    ),

    'recipientsManageSubscriptionPage' => array(
        'Pagina d\'administraziun dils abunaments',
        'Tscherna la pagina per l\'administraziun dals abunaments.',
    ),

    'recipientsPropertyFilter' => array(
        'Filter da caracteristicas',
        'Filtrar ils destinaturs tenor las valurs da caracteristicas.',
    ),

    'recipientsPropertyFilter_comparator' => array(
        'Cumparegliader',
    ),

    'recipientsPropertyFilter_comparators' => array(
        'empty'     => 'è vid',
        'eq'        => '==',
        'gt'        => '>',
        'gte'       => '>=',
        'lt'        => '<',
        'lte'       => '<=',
        'neq'       => '!=',
        'not_empty' => 'n\'è betg vid',
    ),

    'recipientsPropertyFilter_property' => array(
        'Colonna',
    ),

    'recipientsPropertyFilter_value' => array(
        'Valur',
    ),

    'recipientsUnsubscribePage' => array(
        'Pabina per de-abunar',
        'Tscherna la pagina per de-abunar directamain.',
    ),

    'recipientsUsePropertyFilter' => array(
        'Filtrar tenor caracteristicas',
        'Filtrar ils destinaturs tenor caracteristicas e valurs.',
    ),
);

$TL_LANG['orm_avisota_recipient_source'] = array_merge(
    $TL_LANG['orm_avisota_recipient_source'],
    $ormAvisotaRecipientSource
);
