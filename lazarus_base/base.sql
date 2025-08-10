--
-- Table structure for table 'brochure'
--

CREATE TABLE brochure (
  brochure_no int(11) NOT NULL auto_increment,
  url varchar(80) NOT NULL default '',
  pagetitle varchar(80) default NULL,
  title varchar(80) default NULL,
  keywords varchar(200) default NULL,
  description varchar(200) default NULL,
  body text,
  menu_no int(11) default NULL,
  published enum('Y','N') default 'Y',
  function_no int(11) default '0',
  menu_node_no int(11) default '0',
  PRIMARY KEY  (brochure_no),
  KEY url (url)
) TYPE=MyISAM;

--
-- Dumping data for table 'brochure'
--


INSERT INTO brochure VALUES (1,'INDEX','Lazarus Internet Development','Lazarus Internet Development','content management, turnkey, turn key, e-business components, php, mysql','A demonostration site for Lazarus Internet Development\\\'s E-Business Components','<h2>E-Business Components</h2>\r\n<h3>from Lazarus Internet Development</h3>\r\n\r\nE-Business Components from <a href=\\\"http://www.lazarusid.com\\\">Lazarus Internet Development</a> make it easy to manage your web site.  ',1,'Y',0,0);
INSERT INTO brochure VALUES (2,'ADMIN','Wildhaus Kennels Administration','Wildhaus Kennels Administration','admin','Administration Section','This is the administration portion of the Wildhaus Kennels web site.  If you made it this far, chances are very good that you\\\'re working for Wildhaus.\r\n\r\nUse this section to configure and maintain your web site.\r\n\r\n<img src=/images/mvr1916.jpg align=Center>',2,'Y',0,0);


--
-- Table structure for table 'function'
--

CREATE TABLE function (
  function_no int(11) NOT NULL auto_increment,
  name varchar(20) NOT NULL default '',
  PRIMARY KEY  (function_no)
) TYPE=MyISAM;

--
-- Dumping data for table 'function'
--


INSERT INTO function VALUES (1,'User');
INSERT INTO function VALUES (2,'Page');
INSERT INTO function VALUES (3,'Menu');
INSERT INTO function VALUES (4,'Newsletter');
INSERT INTO function VALUES (5,'Link');
INSERT INTO function VALUES (6,'News');

--
-- Table structure for table 'menu'
--

CREATE TABLE menu (
  menu_no int(11) NOT NULL auto_increment,
  name varchar(30) default NULL,
  background_color varchar(12) default NULL,
  graphic_1 varchar(80) default '',
  graphic_2 varchar(80) default '',
  PRIMARY KEY  (menu_no)
) TYPE=MyISAM;

--
-- Dumping data for table 'menu'
--


INSERT INTO menu VALUES (1,'Main Menu','white','','');
INSERT INTO menu VALUES (2,'Admin Menu','white','','');

--
-- Table structure for table 'menu_node'
--

CREATE TABLE menu_node (
  menu_no int(11) NOT NULL default '0',
  menu_node_no int(11) NOT NULL auto_increment,
  link varchar(80) default NULL,
  label varchar(80) default NULL,
  sort_order int(11) NOT NULL default '0',
  function_no int(11) default '0',
  PRIMARY KEY  (menu_node_no),
  KEY customer_no (menu_no,menu_node_no),
  KEY sort_order (sort_order)
) TYPE=MyISAM;

--
-- Dumping data for table 'menu_node'
--


INSERT INTO menu_node VALUES (1,1,'/','Home',1,0);
INSERT INTO menu_node VALUES (1,2,'/admin.php','Log In',100,0);
INSERT INTO menu_node VALUES (2,3,'/menu-browse.php','Menus',2,0);
INSERT INTO menu_node VALUES (2,4,'/brochure-browse.php','Web Pages',1,0);
INSERT INTO menu_node VALUES (2,5,'/','Main Menu',100,0);
INSERT INTO menu_node VALUES (2,6,'/news-browse.php','News',3,0);
INSERT INTO menu_node VALUES (2,14,'/user-browse.php','Users',9,1);
INSERT INTO menu_node VALUES (2,15,'/upload.php','Images',2,0);

--
-- Table structure for table 'registry'
--

CREATE TABLE registry (
  registry_no int(11) NOT NULL auto_increment,
  category varchar(40) NOT NULL default 'generic',
  entity varchar(40) NOT NULL default '',
  value text,
  PRIMARY KEY  (registry_no),
  UNIQUE KEY entity (category,entity)
) TYPE=MyISAM;

CREATE TABLE registry_category (
  category_no int(11) NOT NULL auto_increment,
  name varchar(40) default NULL,
  PRIMARY KEY  (category_no)
) TYPE=MyISAM;

INSERT INTO registry_category VALUES (1,'Show All');
INSERT INTO registry_category VALUES (2,'template-default');
INSERT INTO registry_category VALUES (3,'base');

--
-- Table structure for table 'user'
--

CREATE TABLE user (
  user_no int(11) NOT NULL auto_increment,
  user_id varchar(40) NOT NULL default '',
  email varchar(40) NOT NULL default '',
  name varchar(40) default NULL,
  password varchar(40) default NULL,
  PRIMARY KEY  (user_no),
  KEY email_domain (email),
  KEY user_password (user_id,password)
) TYPE=MyISAM;

--
-- Dumping data for table 'user'
--


INSERT INTO user VALUES (1,'admin','root@localhost','Administrative User','lazarus');

--
-- Table structure for table 'user_function'
--

CREATE TABLE user_function (
  user_no int(11) NOT NULL default '0',
  function_no int(11) NOT NULL default '0',
  PRIMARY KEY  (user_no,function_no)
) TYPE=MyISAM;

--
-- Dumping data for table 'user_function'
--


INSERT INTO user_function VALUES (1,1);
INSERT INTO user_function VALUES (1,2);
INSERT INTO user_function VALUES (1,3);
INSERT INTO user_function VALUES (1,4);
INSERT INTO user_function VALUES (1,5);
INSERT INTO user_function VALUES (1,6);





