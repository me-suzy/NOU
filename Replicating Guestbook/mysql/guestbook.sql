CREATE TABLE useraccounts (
	sitecode char(12) DEFAULT 'char' NOT NULL,
	sitetitle char(12) DEFAULT 'char' NOT NULL,
	emailaddress char(50) DEFAULT 'char' NOT NULL,
	url char(250) DEFAULT 'char' NOT NULL,
	username char(12) DEFAULT 'char' NOT NULL,
	password char(12) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(url)
);

CREATE TABLE links (
	thedate char(25) DEFAULT 'char' NOT NULL,
	sitecode char(12) DEFAULT 'char' NOT NULL,
	url char(150) DEFAULT 'char' NOT NULL,
	email char(100) DEFAULT 'char' NOT NULL,
	linkinfo char(250) DEFAULT 'char' NOT NULL,
	hostinfo char(25) DEFAULT 'char' NOT NULL,
	category char(75) DEFAULT 'char' NOT NULL,
	PRIMARY KEY(url)
);

