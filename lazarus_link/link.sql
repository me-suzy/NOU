--
-- Table structure for table 'link'
--

CREATE TABLE link (
  link_no int(11) NOT NULL auto_increment,
  name varchar(60) default NULL,
  url varchar(60) default NULL,
  submitted_by varchar(60) default NULL,
  approved enum('N','Y') default 'N',
  description text,
  PRIMARY KEY  (link_no)
) TYPE=MyISAM;

--
-- Dumping data for table 'link'
--


INSERT INTO link VALUES (1,'Lazarus Internet Development','http://www.lazarusid.com','clay@lazarusid.com','Y','Custom web design for artists and small companies.');
