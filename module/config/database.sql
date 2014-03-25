-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

--
-- Table `tl_module`
--

CREATE TABLE `tl_module` (
  `avisota_mailing_lists` text NULL,
  `avisota_recipient_fields` text NULL,
  `avisota_form_target` int(10) unsigned NOT NULL default '0',
  `avisota_subscribe_confirmation_message` char(36) NOT NULL default '',
  `avisota_subscribe_form_template` varchar(255) NOT NULL default '',
  `avisota_subscribe_confirmation_page` int(10) unsigned NOT NULL default '0',
  `avisota_subscribe_activation_page` int(10) unsigned NOT NULL default '0',
  `avisota_unsubscribe_show_mailing_lists` char(1) NOT NULL default '',
  `avisota_unsubscribe_confirmation_message` char(36) NOT NULL default '',
  `avisota_unsubscribe_form_template` varchar(255) NOT NULL default '',
  `avisota_unsubscribe_confirmation_page` int(10) unsigned NOT NULL default '0',
  `avisota_subscription_confirmation_message` char(36) NOT NULL default '',
  `avisota_subscription_form_template` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
