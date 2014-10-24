#
# Table structure for table 'tx_medynamicform_domain_model_send_form'
#

CREATE TABLE tx_medynamicform_domain_model_send_form (
    uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
    pid int(11) NOT NULL DEFAULT '0',

    form varchar(255) NOT NULL DEFAULT '',
    fields int(11) unsigned DEFAULT '0' NOT NULL,

    is_dummy_record tinyint(4) unsigned NOT NULL DEFAULT '0',
    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)

);

#
# Table structure for table 'tx_medynamicform_domain_model_send_form_data'
#
CREATE TABLE tx_medynamicform_domain_model_send_form_data (
    uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
    pid int(11) NOT NULL DEFAULT '0',

    value varchar(255) NOT NULL DEFAULT '',
    field varchar(255) NOT NULL DEFAULT '',

    is_dummy_record tinyint(4) unsigned NOT NULL DEFAULT '0',
    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)

);