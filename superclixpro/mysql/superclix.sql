CREATE TABLE profiles (
	name char(50) DEFAULT 'name' NOT NULL,
	company char(50) DEFAULT 'company' NOT NULL,
	sitename char(50) DEFAULT 'sitename' NOT NULL,
	url char(50) DEFAULT 'url' NOT NULL,
	email char(50) DEFAULT 'email' NOT NULL,
	address char(50) DEFAULT 'address' NOT NULL,
	city char(50) DEFAULT 'city' NOT NULL,
	state char(50) DEFAULT 'state' NOT NULL,
	zip char(25) DEFAULT 'zip' NOT NULL,
	country char(50) DEFAULT 'country' NOT NULL,
	ssnumber char(25) DEFAULT 'ssnumber' NOT NULL,
	username char(12) DEFAULT 'username' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	recruiter char(12) DEFAULT 'recruiter' NOT NULL,
	PRIMARY KEY(username)
);

CREATE TABLE stats (
	username char(12) DEFAULT 'username' NOT NULL,
	rawclicks int,
	uniqueclicks int,
	primarycommission int,
	secondarycommission int,
	sdate char(6) DEFAULT 'sdate' NOT NULL,
	mix char(25) DEFAULT 'mix' NOT NULL,
	PRIMARY KEY(mix)
);

CREATE TABLE byuser (
	username char(12) DEFAULT 'username' NOT NULL,
	rawclicks int,
	uniqueclicks int,
	primarycommission int,
	secondarycommission int,
	currentperiod char(6) DEFAULT 'currentperiod' NOT NULL,
	mix char(25) DEFAULT 'mix' NOT NULL,
	PRIMARY KEY(mix)
);

CREATE TABLE bydate (
	rawclicks int,
	uniqueclicks int,
	primarycommission int,
	secondarycommission int,
	sdate char(6) DEFAULT 'currentperiod' NOT NULL,
	mix char(25) DEFAULT 'mix' NOT NULL,
	PRIMARY KEY(mix)
);

CREATE TABLE overview (
	rawclicks int,
	uniqueclicks int,
	primarycommission int,
	secondarycommission int,
	currentperiod char(6) DEFAULT 'currentperiod' NOT NULL,
	mix char(25) DEFAULT 'mix' NOT NULL,
	PRIMARY KEY(mix)
);