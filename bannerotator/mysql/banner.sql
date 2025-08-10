CREATE TABLE banners (
	account char(25) DEFAULT 'account' NOT NULL,
	startdate char(50) DEFAULT 'startdate' NOT NULL,
	username char(12) DEFAULT 'username' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	imagefile char(100) DEFAULT 'imagefile' NOT NULL,
	url char(100) DEFAULT 'url' NOT NULL,
	impressions int,
	clickthroughs int,
	expire int,
	startjulean int,
	stopjulean int,
	expiretype char(25) DEFAULT 'type' NOT NULL,
	email char(50) DEFAULT 'email' NOT NULL,

	PRIMARY KEY(account)
);

CREATE TABLE system (
	startdate char(25) DEFAULT 'startdate' NOT NULL,
	username char(12) DEFAULT 'username' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	impressions int,
	clickthroughs int,
	startjulean int,
	accountcounter int,
	PRIMARY KEY(username)
);

INSERT INTO system VALUES ('','adminusername','adminpassword','0','0','0','0');

