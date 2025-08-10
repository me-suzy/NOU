CREATE TABLE banners (
	startdate char(50) DEFAULT 'startdate' NOT NULL,
	username char(12) DEFAULT 'username' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	acctstatus char(25) DEFAULT 'acctstatus' NOT NULL,
	impressions int,
	aclickthroughs int,
	bclickthroughs int,
	cclickthroughs int,
	sales int,
	credits int,
	salevolume char(50) DEFAULT 'salevolume' NOT NULL,
	email char(50) DEFAULT 'email' NOT NULL,
	lastip char(100) DEFAULT 'lastip' NOT NULL,
	lastdate char(10) DEFAULT 'lastdate' NOT NULL,
	PRIMARY KEY(email,username)
);

CREATE TABLE system (
	startdate char(25) DEFAULT 'startdate' NOT NULL,
	username char(12) DEFAULT 'username' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	impressions int,
	aclickthroughs int,
	bclickthroughs int,
	cclickthroughs int,
	sales int,
	salevolume char(50) DEFAULT 'salevolume' NOT NULL,
	startjulean int,
	accountsnuked int,
	accountsactive int,
	accountstotal int,
	PRIMARY KEY(username)
);


