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
 * last-updated: 2014-03-25T14:15:11+01:00
 */

global $TL_LANG;

if (!array_key_exists('fe_avisota_subscription', $TL_LANG)) {
    $TL_LANG['fe_avisota_subscription'] = array();
}

$feAvisotaSubscription = array(
    'allreadySubscribed'
    => 'Vielen Dank, aber Sie sind bereits für diesen Newsletter angemeldet.',
    'confirm'
    => 'Anmeldung jetzt bestätigen',
    'notSubscribed'
    => 'Sie sind für diesen Newsletter nicht angemeldet.',
    'subscribe'
    => 'Anmelden',
    'subscribeConfirmation'
    => 'Ihre Anmeldung wurde erfolgreich aktiviert.',
    'subscribed'
    => 'Vielen Dank, Sie sind nun angemeldet. Bitte sehen Sie in Ihr Postfach, um die Bestätigungs-Mail anzusehen.',
    'unsubscribe'
    => 'Abmelden',
    'unsubscribed'
    => 'Sie sind jetzt von unserem Newsletter abgemeldet.',
);

$TL_LANG['fe_avisota_subscription'] = array_merge(
    $TL_LANG['fe_avisota_subscription'],
    $feAvisotaSubscription
);
