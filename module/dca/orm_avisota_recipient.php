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
 * Table orm_avisota_recipient
 * Entity Avisota\Contao:Recipient
 */
$user = \BackendUser::getInstance();

$GLOBALS['TL_DCA']['orm_avisota_recipient'] = array
(
	// Entity
	'entity'       => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'       => array
	(
		'dataContainer'    => 'General',
		'switchToEdit'     => true,
		'enableVersioning' => true,
	),
	// DataContainer
	'dca_config'   => array
	(
		'data_provider'  => array
		(
			'default'                  => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityDataProvider',
				'source' => 'orm_avisota_recipient'
			),
			'orm_avisota_subscription' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityDataProvider',
				'source' => 'orm_avisota_subscription'
			),
		),
		'childCondition' => array(
			array(
				'from'   => 'orm_avisota_recipient',
				'to'     => 'orm_avisota_subscription',
				'setOn'  => array
				(
					array(
						'to_field'   => 'recipient',
						'from_field' => 'id',
					),
				),
				'filter' => array
				(
					array
					(
						'local'     => 'recipient',
						'remote'    => 'id',
						'operation' => '=',
					)
				)
			)
		),
	),
	// List
	'list'         => array
	(
		'sorting'           => array
		(
			'mode'        => 2,
			'fields'      => array('email'),
			'panelLayout' => 'filter;sort,search,limit',
		),
		'label'             => array
		(
			'fields' => array('email'),
			'format' => '%s',
		),
		'global_operations' => array
		(
			'migrate' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['migrate'],
				'href'       => 'table=mem_avisota_recipient_migrate',
				'class'      => 'header_recipient_migrate',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			// unused functions or broken at the moment - so do not show them
			/*'all'     => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			),
			'import'  => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['import'],
				'href'       => 'table=orm_avisota_recipient_import&amp;act=edit',
				'class'      => 'header_recipient_import',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'export'  => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['export'],
				'href'       => 'table=orm_avisota_recipient_export&amp;act=edit',
				'class'      => 'header_recipient_export',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'remove'  => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['remove'],
				'href'       => 'table=orm_avisota_recipient_remove&amp;act=edit',
				'class'      => 'header_recipient_remove',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),*/
		),
		'operations'        => array
		(
			/*
			'subscriptions'              => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscriptions'],
				'href'            => 'table=orm_avisota_recipient_subscription',
				'icon'            => 'assets/avisota/core/images/recipient_subscription.png',
			),
			*/
			'edit'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif',
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			),
		),
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'recipient' => array('email'),
			'personals' => array('salutation', 'title', 'forename', 'surname', 'gender'),
			'address'   => array('company', 'street', 'postal', 'city', 'state', 'country'),
		)
	),
	// Fields
	'fields'       => array
	(
		'id'              => array(
			'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['id'],
			'field' => array(
				'id'      => true,
				'type'    => 'string',
				'length'  => '36',
				'options' => array('fixed' => true),
			),
		),
		'createdAt'       => array(
			'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['createdAt'],
			'field' => array(
				'type'          => 'datetime',
				'nullable'      => true,
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'       => array(
			'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['updatedAt'],
			'field' => array(
				'type'          => 'datetime',
				'nullable'      => true,
				'timestampable' => array('on' => 'update')
			)
		),
		'subscriptions'   => array(
			'oneToMany' => array(
				'targetEntity' => 'Avisota\Contao\Entity\Subscription',
				'cascade'      => array('all'),
				'mappedBy'     => 'recipient',
			),
		),
		'email'           => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['email'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'tl_class'   => 'w50',
				'rgxp'       => 'email',
				'mandatory'  => true,
				'maxlength'  => 255,
				'importable' => true,
				'exportable' => true,
				'unique'     => true,
			),
			'field'     => array(
				'unique' => true,
			),
		),
		'salutation'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['salutation'],
			'exclude'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'  => 255,
				'importable' => true,
				'exportable' => true,
				'feEditable' => true,
				'tl_class'   => 'w50'
			),
			'field'     => array(),
		),
		'title'           => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'  => 255,
				'importable' => true,
				'exportable' => true,
				'feEditable' => true,
				'tl_class'   => 'w50',
			),
			'field'     => array(),
		),
		'forename'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['forename'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'   => 255,
				'importable'  => true,
				'exportable'  => true,
				'migrateFrom' => 'firstname',
				'feEditable'  => true,
				'tl_class'    => 'w50',
			),
			'field'     => array(),
		),
		'surname'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['surname'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'   => 255,
				'importable'  => true,
				'exportable'  => true,
				'migrateFrom' => 'lastname',
				'feEditable'  => true,
				'tl_class'    => 'w50',
			),
			'field'     => array(),
		),
		'gender'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['gender'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'select',
			'options'   => array('male', 'female'),
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval'      => array(
				'includeBlankOption' => true,
				'importable'         => true,
				'exportable'         => true,
				'migrateFrom'        => 'gender',
				'feEditable'         => true,
				'tl_class'           => 'clr',
			),
			'field'     => array(),
		),
		'company'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['company'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'   => 255,
				'importable'  => true,
				'exportable'  => true,
				'migrateFrom' => 'company',
				'feEditable'  => true,
				'tl_class'    => 'w50',
			),
			'field'     => array(),
		),
		'street'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['street'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'   => 255,
				'importable'  => true,
				'exportable'  => true,
				'migrateFrom' => 'street',
				'feEditable'  => true,
				'tl_class'    => 'w50',
			),
			'field'     => array(),
		),
		'postal'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['postal'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'   => 255,
				'importable'  => true,
				'exportable'  => true,
				'migrateFrom' => 'postal',
				'feEditable'  => true,
				'tl_class'    => 'w50',
			),
			'field'     => array(),
		),
		'city'            => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['city'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'   => 255,
				'importable'  => true,
				'exportable'  => true,
				'migrateFrom' => 'city',
				'feEditable'  => true,
				'tl_class'    => 'w50',
			),
			'field'     => array(),
		),
		'state'           => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['state'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'   => 255,
				'importable'  => true,
				'exportable'  => true,
				'migrateFrom' => 'state',
				'feEditable'  => true,
				'tl_class'    => 'w50',
			),
			'field'     => array(),
		),
		'country'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['country'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'select',
			'options'   => $this->getCountries(),
			'eval'      => array(
				'includeBlankOption' => true,
				'chosen' => true,
				'importable'         => true,
				'exportable'         => true,
				'migrateFrom'        => 'country',
				'feEditable'         => true,
				'tl_class'           => 'w50',
			),
			'field'     => array(
				'type'    => 'string',
				'length'  => 2,
				'options' => array('fixed' => true),
			),
		),
		'mailingLists'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['mailingLists'],
			'inputType' => 'checkbox',
			'eval'      => array(
				'importable' => true,
				'exportable' => true,
				'mandatory'  => true,
			),
			'field'     => false,
		),
		'addedById'       => array
		(
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedById'],
			'default'    => $user->id,
			'filter'     => true,
			'flag'       => 1,
			'foreignKey' => 'tl_user.name',
			'eval'       => array(
				'exportable' => true,
				'doNotShow'  => true,
				'doNotCopy'  => true
			),
			'field'      => array(
				'type'     => 'integer',
				'nullable' => true,
			),
		),
		'addedByName'     => array
		(
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedByName'],
			'default'    => $user->name,
			'filter'     => true,
			'flag'       => 1,
			'foreignKey' => 'tl_user.name',
			'eval'       => array(
				'exportable' => true,
				'doNotShow'  => true,
				'doNotCopy'  => true
			),
			'field'      => array(
				'type'     => 'string',
				'nullable' => true,
			),
		),
		'addedByUsername' => array
		(
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedByUsername'],
			'default'    => $user->username,
			'filter'     => true,
			'flag'       => 1,
			'foreignKey' => 'tl_user.name',
			'eval'       => array(
				'exportable' => true,
				'doNotShow'  => true,
				'doNotCopy'  => true
			),
			'field'      => array(
				'type'     => 'string',
				'nullable' => true,
			),
		),
	)
);
