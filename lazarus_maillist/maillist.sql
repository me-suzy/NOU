--
-- Table structure for table 'maillist'
--

CREATE TABLE maillist (
  email varchar(80) NOT NULL default '',
  city varchar(40) default NULL,
  state char(2) default NULL,
  fair varchar(20) default NULL,
  PRIMARY KEY  (email)
) TYPE=MyISAM;
