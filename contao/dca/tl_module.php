<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

global $TL_DCA,
       $TL_LANG;

$configOnloadCallback = array(
    array(
        'Avisota\Contao\SubscriptionRecipient\DataContainer\Module',
        'onLoadCallback'
    ),
);

$TL_DCA['tl_module']['config']['onload_callback'] = array_merge(
    $TL_DCA['tl_module']['config']['onload_callback'],
    $configOnloadCallback
);

$metaPalettes = array(
    'avisota_subscribe' => array(
        'title'                => array(
            'name',
            'headline',
            'type',
        ),
        'avisota_subscription' => array(
            'avisota_mailing_lists',
            'avisota_recipient_fields',
            'avisota_subscribe_confirmation_message',
        ),
        'template'             => array(
            'tableless',
            'avisota_subscribe_form_template',
            'avisota_form_target',
            'avisota_subscribe_confirmation_page',
            'avisota_subscribe_activation_page',
            'avisota_subscribe_activate_confirmation_page',
        ),
        'protected'            => array(
            ':hide',
            'protected',
        ),
        'expert'               => array(
            ':hide',
            'guests',
            'cssID',
            'space',
        )
    ),

    'avisota_activation' => array
    (
        'title'     => array(
            'name',
            'headline',
            'type',
        ),
        'template'  => array(
            'tableless',
            'avisota_activation_redirect_page',
            'avisota_activation_confirmation_page',
        ),
        'protected' => array(
            ':hide',
            'protected',
        ),
        'expert'    => array(
            ':hide',
            'guests',
            'cssID',
            'space',
        )
    ),

    'avisota_unsubscribe' => array(
        'title'                => array(
            'name',
            'headline',
            'type'
        ),
        'avisota_subscription' => array(
            'avisota_mailing_lists',
            'avisota_unsubscribe_show_mailing_lists',
            'avisota_unsubscribe_confirmation_message',
        ),
        'template'             => array(
            'tableless',
            'avisota_unsubscribe_form_template',
            'avisota_form_target',
            'avisota_unsubscribe_confirmation_page',
        ),
        'protected'            => array(
            ':hide',
            'protected',
        ),
        'expert'               => array(
            ':hide',
            'guests',
            'cssID',
            'space',
        )
    ),

    'avisota_subscription' => array(
        'title'                => array(
            'name',
            'headline',
            'type'
        ),
        'avisota_subscription' => array(
            'avisota_mailing_lists',
            'avisota_recipient_fields',
            'avisota_subscription_confirmation_message',
        ),
        'template'             => array(
            'tableless',
            'avisota_subscription_form_template',
        ),
        'protected'            => array(
            ':hide',
            'protected'
        ),
        'expert'               => array(
            ':hide',
            'avisota_form_target',
            'guests',
            'cssID',
            'space'
        )
    ),
);

$TL_DCA['tl_module']['metapalettes'] = array_merge(
    $TL_DCA['tl_module']['metapalettes'],
    $metaPalettes
);

$fields = array(
    'avisota_mailing_lists' => array(
        'exclude'          => true,
        'label'            =>
            &$TL_LANG['tl_module']['avisota_mailing_lists'],
        'inputType'        => 'checkbox',
        'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            \Avisota\Contao\Core\CoreEvents::CREATE_MAILING_LIST_OPTIONS,
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
        'eval'             => array('multiple' => true)
    ),

    'avisota_recipient_fields' => array(
        'exclude'          => true,
        'label'            =>
            &$TL_LANG['tl_module']['avisota_recipient_fields'],
        'inputType'        => 'checkboxWizard',
        'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            \Avisota\Contao\SubscriptionRecipient\RecipientDataContainerEvents::CREATE_EDITABLE_RECIPIENT_FIELD_OPTIONS,
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
        'eval'             => array(
            'multiple' => true,
        ),
        'load_callback'    => array(
            array(
                'Avisota\Contao\SubscriptionRecipient\DataContainer\Module',
                'injectRequiredRecipientFields'
            ),
        ),
        'save_callback'    => array(
            array(
                'Avisota\Contao\SubscriptionRecipient\DataContainer\Module',
                'injectRequiredRecipientFields'
            ),
        ),
    ),

    'avisota_form_target' => array(
        'exclude'   => true,
        'label'     =>
            &$TL_LANG['tl_module']['avisota_form_target'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'fieldType' => 'radio',
            'tl_class'  => 'clr',
        ),
    ),

    'avisota_subscribe_form_template' => array(
        'exclude'          => true,
        'label'            =>
            &$TL_LANG['tl_module']['avisota_subscribe_form_template'],
        'inputType'        => 'select',
        'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            \Avisota\Contao\SubscriptionRecipient\RecipientDataContainerEvents::CREATE_SUBSCRIBE_TEMPLATE_OPTIONS,
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
        'eval'             => array(
            'tl_class' => 'w50'
        ),
    ),

    'avisota_subscribe_confirmation_message' => array(
        'exclude'          => true,
        'label'            =>
            &$TL_LANG['tl_module']['avisota_subscribe_confirmation_message'],
        'inputType'        => 'select',
        'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            \Avisota\Contao\Message\Core\MessageEvents::CREATE_BOILERPLATE_MESSAGE_OPTIONS,
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
        'eval'             => array(
            'includeBlankOption' => true,
            'tl_class'           => 'w50',
        ),
    ),

    'avisota_subscribe_confirmation_page' => array(
        'exclude'   => true,
        'label'     =>
            &$TL_LANG['tl_module']['avisota_subscribe_confirmation_page'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'fieldType' => 'radio',
        ),
    ),

    'avisota_subscribe_activation_page' => array(
        'exclude'   => true,
        'label'     =>
            &$TL_LANG['tl_module']['avisota_subscribe_activation_page'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'fieldType' => 'radio',
        ),
    ),

    'avisota_subscribe_activate_confirmation_page' => array(
        'exclude'   => true,
        'label'     =>
            &$TL_LANG['tl_module']['avisota_subscribe_activate_confirmation_page'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'fieldType' => 'radio'
        ),
    ),

    'avisota_activation_redirect_page' => array(
        'exclude'   => true,
        'label'     =>
            &$TL_LANG['tl_module']['avisota_activation_redirect_page'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'fieldType' => 'radio',
            'tl_class'  => 'clr',
        ),
    ),

    'avisota_activation_confirmation_page' => array(
        'exclude'   => true,
        'label'     =>
            &$TL_LANG['tl_module']['avisota_activation_confirmation_page'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'fieldType' => 'radio',
            'tl_class'  => 'clr',
        ),
    ),

    'avisota_unsubscribe_show_mailing_lists' => array(
        'exclude'   => true,
        'label'     =>
            &$TL_LANG['tl_module']['avisota_unsubscribe_show_mailing_lists'],
        'inputType' => 'checkbox',
        'eval'      => array(
            'tl_class' => 'm12 w50',
        ),
    ),

    'avisota_unsubscribe_confirmation_message' => array(
        'exclude'          => true,
        'label'            =>
            &$TL_LANG['tl_module']['avisota_unsubscribe_confirmation_message'],
        'inputType'        => 'select',
        'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            \Avisota\Contao\Message\Core\MessageEvents::CREATE_BOILERPLATE_MESSAGE_OPTIONS,
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
        'eval'             => array(
            'includeBlankOption' => true,
            'tl_class'           => 'w50',
        ),
    ),

    'avisota_unsubscribe_form_template' => array(
        'exclude'          => true,
        'label'            =>
            &$TL_LANG['tl_module']['avisota_unsubscribe_form_template'],
        'inputType'        => 'select',
        'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            \Avisota\Contao\SubscriptionRecipient\RecipientDataContainerEvents::CREATE_UNSUBSCRIBE_TEMPLATE_OPTIONS,
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
        'eval'             => array(
            'tl_class' => 'w50'
        ),
    ),

    'avisota_unsubscribe_confirmation_page' => array(
        'exclude'   => true,
        'label'     =>
            &$TL_LANG['tl_module']['avisota_unsubscribe_confirmation_page'],
        'inputType' => 'pageTree',
        'eval'      => array(
            'fieldType' => 'radio',
        ),
    ),

    'avisota_subscription_form_template' => array(
        'exclude'          => true,
        'label'            =>
            &$TL_LANG['tl_module']['avisota_subscription_form_templateddd'],
        'inputType'        => 'select',
        'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            \Avisota\Contao\SubscriptionRecipient\RecipientDataContainerEvents::CREATE_SUBSCRIPTION_TEMPLATE_OPTIONS,
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
        'eval'             => array(
            'tl_class' => 'w50',
        ),
    ),

    'avisota_subscription_confirmation_message' => array(
        'exclude'          => true,
        'label'            =>
            &$TL_LANG['tl_module']['avisota_subscription_confirmation_message'],
        'inputType'        => 'select',
        'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            \Avisota\Contao\Message\Core\MessageEvents::CREATE_BOILERPLATE_MESSAGE_OPTIONS,
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
        'eval'             => array(
            'includeBlankOption' => true,
            'tl_class'           => 'w50',
        ),
    ),
);

$TL_DCA['tl_module']['fields'] = array_merge(
    $TL_DCA['tl_module']['fields'],
    $fields
);
