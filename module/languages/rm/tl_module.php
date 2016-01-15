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
 * last-updated: 2014-03-25T14:15:20+01:00
 */

global $TL_LANG;

$tlModule = array(
    'avisota_cleanup_legend' => 'Far urden',

    'avisota_list_legend' => 'Glista dals mailings',

    'avisota_mail_legend' => 'Configuraziun dad e-mail',

    'avisota_notification_legend' => 'Regurdanza',

    'avisota_reader_legend' => 'Lectur dal mailing',

    'avisota_subscription_legend' => 'Abunament',

    'avisota_activation_confirmation_page' => array(
        'Pagina da confermaziun',
        'Tscherna la pagina da confermaziun. L\'utilisader vegn renvià '
        . 'a questa pagina suenter cliccar sin la colliaziun d\'activaziun.',
    ),

    'avisota_activation_redirect_page' => array(
        'Pagina da renviar',
        'Tscherna ina pagina per renviar. L\'utilisader vegn renvià '
        . 'a questa paigna sche el avra la pagina senza in token d\'abunament valid.',
    ),

    'avisota_categories' => array(
        'Categorias',
        'Tscherna las categorias, ord las qualas ils Mailings duain vegnir mussads.',
    ),

    'avisota_form_target' => array(
        'Pagina da destinaziun dal formular (na betg la pagina da confermaziun!)',
        'Tscherna ina pagina a la quala las datas dal formular vegnan tramessas (questa pagina vegn definida sco '
        . 'acziun da &lt;form&gt;!). Ta ragorda che il modul sto vegnir agiuntà a la pagina da destinaziun!',
    ),

    'avisota_list_template' => array(
        'Template da glista',
        'Tscherna il template per la glista da mailings',
    ),

    'avisota_mailing_lists' => array(
        'Glistas dad e-mail tschernidas/tscherniblas',
        'Tscherna las glistas dad e-mail che vegnan abunadas '
        . 'u mussadas sche il champ d\'endataziun per glistas dad e-mail è visibel.',
    ),

    'avisota_reader_template' => array(
        'Template da lectur',
        'Tscherna il template per il lectur da mailings.',
    ),

    'avisota_recipient_fields' => array(
        'Datas persunalas',
        'Tscherna las datas persunalas supplementaras che duain vegnir dumandadas.',
    ),

    'avisota_subscribe_activate_confirmation_page' => array(
        'Pagina da confermaziun da l\'activaziun',
        'Tscherna ina pagina da confermaziun per l\'activaziun. Sche ti n\'utiliseschas betg ina pagina d\'activaziun '
        . 'seperata vegn l\'utilisader renvià a questa pagina suenter cliccar sin la colliaziun d\'activaziun.',
    ),

    'avisota_subscribe_activation_page' => array(
        'Pagina d\'activaziun',
        'Tscherna la pagina d\'activaziun. La colliaziun d\'activaziun vegn a mussar sin questa pagina. '
        . 'Fa stim che ti stos agiuntar a questa pagina in modul "Abunar" u "Activar".',
    ),

    'avisota_subscribe_confirmation_message' => array(
        'Messadi da confermaziun',
        'Tscherna in model per il messadi da confermaziun.',
    ),

    'avisota_subscribe_confirmation_page' => array(
        'Pagina da confermaziun per abunar',
        'Tscherna la pagina da confermaziun. L\'utilisader vegn renvià '
        . 'a questa pagina suenter ch\'el ha dumandà per in abunament.',
    ),

    'avisota_subscribe_form_template' => array(
        'Template dal formular',
        'Tscherna in template da forumlar.',
    ),

    'avisota_subscription_confirmation_message' => array(
        'Pagina da confermaziun',
        'Tscherna ina pagina da confermaziun.',
    ),

    'avisota_subscription_form_template' => array(
        'Template dal forumlar',
        'Tscherna in template da forumlar persunalisà.',
    ),

    'avisota_template_unsubscribe' => array(
        'Template per il forumular',
        'Tscherna il template per il formular per de-abunar.',
    ),

    'avisota_unsubscribe_confirmation_message' => array(
        'Messadi da confermaziun',
        'Tscherna in model per il messadi da confermaziun.',
    ),

    'avisota_unsubscribe_confirmation_page' => array(
        'Pagina da confermaziun',
        'Tscherna ina pagina da confermaziun.',
    ),

    'avisota_unsubscribe_form_template' => array(
        'Template dal formular',
        'Tscherna in template da forumlar.',
    ),

    'avisota_unsubscribe_show_mailing_lists' => array(
        'Mussar la selecziun da glistas dad e-mail',
        'Laschar tscherner l\'utilisader tge glistas dad e-mail che duain vegnir abunadas.',
    ),

    'avisota_view_page' => array(
        'Pagina da visualisaziun',
        'Tscherna qua ina pagina, sin la quala ils mailings duain vegnir visualisads. Na vegn nagina pagina '
        . 'tsschernida, vegn la pagina nudada en la categoria utilisada per la visualisaziun online',
    ),
);

$TL_LANG['tl_module'] = array_merge(
    $TL_LANG['tl_module'],
    $tlModule
);
