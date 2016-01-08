<?php
/**
 * Translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 *
 * @link    http://help.transifex.com/intro/translating.html
 * @link    https://www.transifex.com/projects/p/avisota-contao/language/pl/
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *
 * last-updated: 2014-03-25T14:15:14+01:00
 */

global $TL_LANG;

$ormAvisotaRecipient = array(
    'subscription_legend' => 'Subskrypcja',

    'subscription_mailingList' => 'Lista mailingowa: %s',

    'personals_legend' => 'Dane osobiste',

    'recipient_legend' => 'Odbiorca',

    'addedBy' => array(
        'Dodany przez',
        'Użytkownik Contao, który dodał tego odbiorcę.',
        'przez %s',
        'przez usuniętego użytkownika',
    ),

    'addedOn' => array(
        'Dodany',
        'Data subskrypcji.',
        'dodany %s',
    ),

    'confirmed' => array(
        'Potwierdzone',
        'To konto zostało potwierdzone.',
    ),

    'copy' => array(
        'Duplikuj odbiorcę',
        'Duplikuj odbiorcę ID %s',
    ),

    'createdAt' => array(
        'Utworzony',
    ),

    'delete' => array(
        'Usuń odbiorcę',
        'Usuń odbiorcę ID %s',
    ),

    'delete_no_blacklist' => array(
        'Usuń odbiorcę bez umieszczenia na czarnej liście',
        'Usuń odbiorcę ID %s bez umieszczenia na czarnej liście',
    ),

    'edit' => array(
        'Edytuj odbiorcę',
        'Edytuj odbiorcę ID %s',
    ),

    'email' => array(
        'E-mail',
        'Proszę wprowadzić adres e-mail.',
    ),

    'export' => array(
        'Eksport CSV',
        'Eksportuj odbiorców do pliku CSV.',
    ),

    'forename' => array(
        'Imię',
        'Proszę wprowadzić imię odbiorcy.',
    ),

    'gender' => array(
        'Płeć',
        'Proszę wybrać płeć odbiorcy.',
    ),

    'import' => array(
        'Import CSV',
        'Importuj odbiorców z pliku CSV.',
    ),

    'lists' => array(
        'Listy mailingowe',
        'Proszę wybrać subskrybowane listy mailingowe.',
    ),

    'migrate' => array(
        'Przenieś',
        'Przenieś odbiorców z systemu newslettera Contao.',
    ),

    'new' => array(
        'Nowy odbiorca',
        'Dodaj nowego odbiorcę',
    ),

    'notify' => array(
        'Powiadom odbiorcę',
        'Powiadom odbiorcę ID %s',
    ),

    'remove' => array(
        'Usuń CSV',
        'Usuń odbiorców z pliku CSV.',
    ),

    'salutation' => array(
        'Powitanie',
        'Proszę wybrać preferowane powitanie.',
    ),

    'show' => array(
        'Szczegóły odbiorcy',
        'Pokaż szczegóły odbiorcy ID %s',
    ),

    'subscriptionAction' => array(
        'Aktywacja',
        'Proszę wybrać metodę aktywacji dla subskrypcji na nowych listach mailingowych.',
    ),

    'surname' => array(
        'Nazwisko',
        'Proszę wprowadzić nazwisko odbiorcy.',
    ),

    'title' => array(
        'Tytuł',
        'Proszę wprowadzić tytuł odbiorcy.',
    ),

    'updatedAt' => array(
        'Ostatnia modyfikacja',
    ),
);

$TL_LANG['orm_avisota_recipient'] = array_merge(
    $TL_LANG['orm_avisota_recipient'],
    $ormAvisotaRecipient
);
