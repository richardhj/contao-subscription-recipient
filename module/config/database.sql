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
  `avisota_show_mailing_lists` char(1) NOT NULL default '',
  `avisota_mailing_lists` text NULL,
  `avisota_recipient_fields` text NULL,
  `avisota_subscribe_confirmation_page` int(10) unsigned NOT NULL default '0',
  `avisota_unsubscribe_confirmation_page` int(10) unsigned NOT NULL default '0',
  `avisota_template_subscribe` varchar(255) NOT NULL default '',
  `avisota_template_unsubscribe` varchar(255) NOT NULL default '',
  `avisota_template_subscription` varchar(255) NOT NULL default '',
  `avisota_form_target` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
