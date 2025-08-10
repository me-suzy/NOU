CREATE TABLE banners (
	startdate char(50) DEFAULT 'startdate' NOT NULL,
	username char(12) DEFAULT 'username' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	impressions int,
	clickthroughs int,
	email char(50) DEFAULT 'email' NOT NULL,
	lastip char(100) DEFAULT 'lastip' NOT NULL,
	lastdate char(10) DEFAULT 'lastdate' NOT NULL,
	PRIMARY KEY(email,username)
);

CREATE TABLE bsystem (
	startdate char(25) DEFAULT 'startdate' NOT NULL,
	username char(12) DEFAULT 'username' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	impressions int,
	clickthroughs int,
	startjulean int,
	accountsnuked int,
	accountsactive int,
	accountstotal int,
	PRIMARY KEY(username)
);


