CREATE TABLE stats (
	date char(6) DEFAULT 'date' NOT NULL,
	username char(12) DEFAULT 'char' NOT NULL,
	password char(12) DEFAULT 'char' NOT NULL,
	email char(50) DEFAULT 'char' NOT NULL,
	rimpress int,
	uimpress int,
	rclicks int,
	uclicks int,
	myrimpress int,
	myuimpress int,
	myrclicks int,
	myuclicks int,
	banner char(50) DEFAULT 'banner' NOT NULL,
	url char(50) DEFAULT 'url' NOT NULL,
	mix char(50) DEFAULT 'mix' NOT NULL,
	PRIMARY KEY(mix)
);
CREATE TABLE credits (
	username char(12) DEFAULT 'char' NOT NULL,
	credits int,
	PRIMARY KEY(username)
);
