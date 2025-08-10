--
-- Table structure for table 'event'
--

DROP TABLE IF EXISTS event;
CREATE TABLE event (
  event_no int(11) NOT NULL auto_increment,
  event_begin date NOT NULL default '0000-00-00',
  name varchar(80) NOT NULL default '',
  location varchar(80) NOT NULL default '',
  begin_hour varchar(10) default NULL,
  end_hour varchar(10) default NULL,
  event_end date NOT NULL default '0000-00-00',
  article_no int default 0,
  PRIMARY KEY  (event_no),
  KEY event_date (event_begin)
) TYPE=MyISAM;

DELETE FROM function WHERE name='Event';
INSERT INTO function (name) VALUES ('Event');

DELETE FROM menu_node WHERE link='/event-browse.php';
INSERT INTO menu_node (menu_no, link, label) VALUES (2, '/event-browse.php', 'Events');
