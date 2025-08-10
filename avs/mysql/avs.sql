CREATE TABLE sites (
	sitename char(100) DEFAULT 'sitename' NOT NULL,
	category char(100) DEFAULT 'category' NOT NULL,
	warningurl char(100) DEFAULT 'warningurl' NOT NULL,
	contenturl char(100) DEFAULT 'contenturl' NOT NULL,
	name char(50) DEFAULT 'name' NOT NULL,
	email char(50) DEFAULT 'email' NOT NULL,
	phone char(25) DEFAULT 'phone' NOT NULL,
	address char(50) DEFAULT 'address' NOT NULL,
	city char(50) DEFAULT 'city' NOT NULL,
	state char(50) DEFAULT 'state' NOT NULL,
	zip char(25) DEFAULT 'zip' NOT NULL,
	country char(50) DEFAULT 'country' NOT NULL,
	ssnumber char(25) DEFAULT 'ssnumber' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	exclusive char(5) DEFAULT 'exclusive' NOT NULL,
	webmastercode char(50) DEFAULT 'webmastercode' NOT NULL,
	status char(25) DEFAULT 'status' NOT NULL,
	sitenumber char(25) DEFAULT 'sitenumber' NOT NULL,
	PRIMARY KEY(sitenumber ,warningurl,contenturl)
);

CREATE TABLE counter (
	sitecounter char(25) DEFAULT 'sitenumber' NOT NULL,
	PRIMARY KEY(sitecounter)
);

CREATE TABLE commissions (
	username char(12) DEFAULT 'username' NOT NULL,
	cardnum char(21) DEFAULT 'cardnum' NOT NULL,
	commission char(12) DEFAULT 'commission' NOT NULL,
	webmastercode char(50) DEFAULT 'webmastercode' NOT NULL,
	sitenumber char(25) DEFAULT 'sitenumber' NOT NULL,
	email char(50) DEFAULT 'email' NOT NULL,
	PRIMARY KEY(username)
);

CREATE TABLE useraccounts (
	paymentmethod char(35) DEFAULT 'paymentmethod' NOT NULL,
	accountype char(35) DEFAULT 'accountype' NOT NULL,
	amount char(12) DEFAULT 'amount' NOT NULL,
	username char(12) DEFAULT 'username' NOT NULL,
	password char(12) DEFAULT 'password' NOT NULL,
	cardnum char(21) DEFAULT 'cardnum' NOT NULL,
	expdate char(6) DEFAULT 'expdate' NOT NULL,
	name char(50) DEFAULT 'name' NOT NULL,
	address char(50) DEFAULT 'address' NOT NULL,
	city char(50) DEFAULT 'city' NOT NULL,
	state char(50) DEFAULT 'state' NOT NULL,
	zip char(25) DEFAULT 'zip' NOT NULL,
	email char(50) DEFAULT 'email' NOT NULL,
	signupdate int,
	expiredate int,
	PRIMARY KEY(username)
);

INSERT INTO counter VALUES ('1');
