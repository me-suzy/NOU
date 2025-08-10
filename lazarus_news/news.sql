--
-- Definitions for table article
--

DROP TABLE IF EXISTS article;
CREATE TABLE article (
  article_no int(11) NOT NULL auto_increment,
  publish_date date NOT NULL default '0000-00-00',
  unpublish_date date NOT NULL default '0000-00-00',
  author int(11) default 1,
  menu_no int(11) default 1,
  description text,
  keywords text,
  body text,
  title varchar(80) default NULL,
  PRIMARY KEY  (article_no),
  KEY publish (publish_date,unpublish_date)
) TYPE=MyISAM;

--
-- Dumping data for table 'article'
--


INSERT INTO article VALUES (1,'2001-12-01','2002-01-30',1,1,'This website has added a news system.  Look for updates on current happenings here.','review, wildhaus','The new web system is accessible to people with the News permission from the admin menu.  Anybody responsible for maintaining content on this site is encouraged to have a look.','New Web Site Feature');


--
-- Updating the admin menu
--

DELETE FROM menu_node WHERE link='/news-browse.php';
INSERT INTO menu_node (menu_no, link, label) VALUES (2, '/news-browse.php', 'News');

---
--- Add the news permission as necessary
---

DELETE FROM function WHERE name='News';
INSERT INTO function (name) VALUES ('News');
