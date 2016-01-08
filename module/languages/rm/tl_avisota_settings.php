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
 * last-updated: 2014-03-25T14:15:19+01:00
 */

global $TL_LANG;

$tlAvisotaSettings = array(
    'cleanup_legend' => 'Far urden',

    'notification_legend' => 'Avis',

    'subscription_recipient_legend' => 'Destinaturs',

    'avisota_cleanup_time' => array(
        'Dis enfin ch\'i vegn stizzà',
        'Endatescha il dumber da dis avant che abunents betg confermads vegnan stizzads.',
    ),

    'avisota_do_cleanup' => array(
        'Stizzar abunents betg confermads',
        'Stizzar abunents che na conferman betg l\'abunament suenter in tschert dumber da dis',
    ),

    'avisota_notification_count' => array(
        'Dumber d\'avis',
        'Endatescha il dumber d\'avis che duain vegnir tramess. Il temp tranter ils avis vegn auzà per 50%.',
    ),

    'avisota_notification_mail' => array(
        'E-Mail d\'avis',
        'Tscherna la boilerplate per l\'e-mail d\'avis.',
    ),

    'avisota_notification_time' => array(
        'Dis enfin che l\'avis vegn tramess',
        'Endatescha il dumber da dis avant ch\'in avis vegn tramess.',
    ),

    'avisota_send_notification' => array(
        'Trametter avis',
        'Trametter in avis, sche l\'abunament n\'è betg confermà suenter in tschert dumber da dis.',
    ),

    'avisota_subscription_recipient_cleanup' => array(
        'Far urden cun destinaturs',
        'Sch\'in destinatur allontanescha tut ils abunaments duail il destinatur era vegnir stizzà.',
    ),
);

$TL_LANG['tl_avisota_settings'] = array_merge(
    $TL_LANG['tl_avisota_settings'],
    $tlAvisotaSettings
);
