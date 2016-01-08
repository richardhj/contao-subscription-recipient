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
 * last-updated: 2014-03-25T14:15:11+01:00
 */

global $TL_LANG;

$feAvisotaSubscription = array(
    'allreadySubscribed'
    => 'Grazia fitg, ti has dentant gia abunà nos newsletter.',
    'confirm'
    => 'Confermar abunaments',
    'manage_subscription'
    => 'Administrat tes abunament',
    'notSubscribed'
    => 'Ti n\'has betg abunà nos newsletter',
    'subscribe'
    => 'Abunar',
    'subscribeConfirmation'
    => 'Tes abunament è vegnì activà cun success.',
    'subscribed'
    => 'Grazia fitg, ti es ussa abunnent. Ti has retschiert in e-mail da confermaziun.',
    'unsubscribe'
    => 'De-abunar',
    'unsubscribe_direct'
    => 'De-abunar',
    'unsubscribed'
    => 'Ti has ussa de-abunà nos newsletter'
);

$TL_LANG['fe_avisota_subscription'] = array_merge(
    $TL_LANG['fe_avisota_subscription'],
    $feAvisotaSubscription
);
